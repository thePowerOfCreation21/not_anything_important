<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\StudentFinancialResource;
use App\Models\StudentFinancialModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class StudentFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentFinancialModel::class)
            ->setResource(StudentFinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'payment_type' => ['required', 'string', 'max:50'],
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
                    'payment_type' => ['string', 'max:50'],
                    'check_number' => ['nullable', 'string', 'max:50'],
                    'receipt_number' => ['nullable', 'string', 'max:50'],
                    'amount' => ['int', 'min:0', 'max:100000000'],
                    'date' => ['date_format:Y-m-d'],
                    'payment_date' => ['nullable', 'date_format:Y-m-d'],
                    'paid' => ['bool'],
                    'check_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3000']
                ],
                'getQuery' => [
                    'student_id' => ['string', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'check_image' => ['file', 'nullable']
            ])
            ->setQueryToEloquentClosures([
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
}
