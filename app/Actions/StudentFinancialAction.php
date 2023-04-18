<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\StudentFinancialResource;
use App\Models\StudentFinancialModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class StudentFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentFinancialModel::class)
            ->setResource(StudentFinancialResource::class)
            ->setValidationRules([
                'updatePaidByList' => [
                    'list' => ['array', 'max:1000'],
                    'list.*' => ['array'],
                    'list.*.student_financial_id' => ['required', 'integer'],
                    'list.*.paid' => ['required', 'boolean']
                ],
                'store' => [
                    'payment_type' => ['required', 'in:check,cash'],
                    'check_number' => ['nullable', 'string', 'max:50'],
                    'receipt_number' => ['nullable', 'string', 'max:50'],
                    'student_id' => ['required', 'string', 'max:20'],
                    'amount' => ['required', 'int', 'min:0', 'max:100000000'],
                    'date' => ['required', 'date_format:Y-m-d'],
                    'payment_date' => ['nullable', 'date_format:Y-m-d'],
                    'paid' => ['bool'],
                    'check_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3000']
                ],
                'update' => [
                    'payment_type' => ['in:check,cash'],
                    'check_number' => ['nullable', 'string', 'max:50'],
                    'receipt_number' => ['nullable', 'string', 'max:50'],
                    'amount' => ['int', 'min:0', 'max:100000000'],
                    'date' => ['date_format:Y-m-d'],
                    'payment_date' => ['nullable', 'date_format:Y-m-d'],
                    'paid' => ['bool'],
                    'check_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3000']
                ],
                'sendSms' => [
                    'student_financials' => ['required', 'array', 'max:100'],
                    'student_financials.*' => ['integer', 'between:1,999999999999999999']
                ],
                'getQuery' => [
                    'payment_type' => ['in:check,cash'],
                    'student_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'can_send_sms' => ['boolean'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getByStudent' => [
                    'payment_type' => ['in:check,cash'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'can_send_sms' => ['boolean'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
                'payment_date' => ['jalali_to_gregorian:Y-m-d'],
                'check_image' => ['file', 'nullable']
            ])
            ->setQueryToEloquentClosures([
                'payment_type' => function(&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('payment_type', $query['payment_type']);
                },
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['from_date']);
                },
                'can_send_sms' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->canSendSms($query['can_send_sms']);
                }
            ]);

        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        $data['paid'] = $data['paid'] ?? false;

        /*
        $generalStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('student_financial', $data['educational_year']);

        if ($data['paid'])
        {
            $generalStatistic->paid += $data['amount'];
        }
        else
        {
            $generalStatistic->not_paid += $data['amount'];
        }

        $generalStatistic->save();
        */

        return parent::store($data, $storing);
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        $updating = function (&$eloquent, $updateData) use ($updating)
        {
            if(isset($updateData['date']))
            {
                $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
            }

            foreach ($eloquent->get() AS $studentFinancial)
            {
                /*
                $studentFinancialGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('student_financial', $studentFinancial->educational_year);

                if ($studentFinancial->paid)
                {
                    $studentFinancialGeneralStatistic->paid -= $studentFinancial->amount;
                }
                else
                {
                    $studentFinancialGeneralStatistic->not_paid -= $studentFinancial->amount;
                }

                $studentFinancialGeneralStatistic->save();

                $newGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('student_financial', $updateData['educational_year'] ?? $studentFinancial->educational_year);

                if ($updateData['paid'] ?? $studentFinancial->paid)
                {
                    $newGeneralStatistic->paid += $updateData['amount'] ?? $studentFinancial->amount;
                }
                else
                {
                    $newGeneralStatistic->not_paid += $updateData['amount'] ?? $studentFinancial->amount;
                }

                $newGeneralStatistic->save();
                */

                if (array_key_exists('check_image', $updateData) && is_file($studentFinancial->check_image))
                {
                    unlink($studentFinancial->check_image);
                }
            }

            if (is_callable($updating))
            {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }

    /**
     * @param callable|null $deleting
     * @return mixed
     */
    public function delete(callable $deleting = null): mixed
    {
        $deleting = function (&$eloquent) use ($deleting)
        {
            foreach ($eloquent->get() AS $studentFinancial)
            {
                /*
                $studentFinancialGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('student_financial', $studentFinancial->educational_year);

                if ($studentFinancial->paid)
                {
                    $studentFinancialGeneralStatistic->paid -= $studentFinancial->amount;
                }
                else
                {
                    $studentFinancialGeneralStatistic->not_paid -= $studentFinancial->amount;
                }

                $studentFinancialGeneralStatistic->save();
                */

                if (is_file($studentFinancial->check_image))
                {
                    unlink($studentFinancial->check_image);
                }
            }

            if (is_callable($deleting))
            {
                $deleting($eloquent);
            }
        };

        return parent::delete($deleting);
    }

    /**
     * @return bool
     * @throws CustomException
     */
    public function sendSmsByRequest (): bool
    {
        return $this->sendSmsByIds(
            $this->getDataFromRequest()['student_financials'] ?? []
        );
    }

    /**
     * @param array $studentFinancialIds
     * @return bool
     */
    public function sendSmsByIds (array $studentFinancialIds): bool
    {
        $currentTime = time();

        foreach (
            StudentFinancialModel::query()
                ->whereHas('student')
                ->canSendSms()
                ->whereIn('id', $studentFinancialIds)
                ->get()
            AS $studentFinancial
        )
        {
            if (strtotime($studentFinancial->date) <= $currentTime)
            {
                // TODO: send overdue payment sms
            }
            else
            {
                // TODO: send near-due payment sms
            }
        }

        return true;
    }

    /**
     * @return int
     * @throws CustomException
     */
    public function updatePaidByListByRequest ()
    {
        $data = $this
            ->setValidationRule('updatePaidByList')
            ->getDataFromRequest();

        $paidList = [];
        $notPaidList = [];

        foreach ($data['list'] AS $item)
        {
            if ($item['paid'])
                $paidList[] = $item['student_financial_id'];
            else
                $notPaidList[] = $item['student_financial_id'];
        }

        if(! empty($paidList))
            StudentFinancialModel::query()
                ->whereIn('id', $paidList)
                ->update([
                    'paid' => true
                ]);

        if(! empty($notPaidList))
            StudentFinancialModel::query()
                ->whereIn('id', $notPaidList)
                ->update([
                    'paid' => false
                ]);

        return true;
    }
}
