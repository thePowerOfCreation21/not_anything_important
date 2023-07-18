<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherFinancialModel;
use App\Helpers\PardisanHelper;
use App\Http\Resources\TeacherFinancialResource;

class TeacherFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(TeacherFinancialModel::class)
            ->setResource(TeacherFinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'teacher_id' => ['required', 'string', 'max:20'],
                    'amount' => ['required', 'int', 'min:0', 'max:100000000'],
                    'date' => ['required', 'string'],
                    'receipt_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3000'],
                    'description' => ['nullable', 'string', 'max:2500']
                ],
                'update' => [
                    'amount' => ['int', 'min:0', 'max:100000000'],
                    'date' => ['string'],
                    'receipt_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:3000'],
                    'description' => ['nullable', 'string', 'max:2500']
                ],
                'getQuery' => [
                    'teacher_id' => ['string', 'max:20'],
                    'educational_year' => ['string', 'max:50'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ],
                'getByTeacher' => [
                    'educational_year' => ['string', 'max:50'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ],
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
                'receipt_image' => ['nullable', 'file']
            ])
            ->setQueryToEloquentClosures([
                'teacher_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'educational_year' => function (&$eloquent, $query) {
                    if ($query['educational_year']  != '*') {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
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

        /*
        $generalStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $data['educational_year']);

        $generalStatistic->paid += $data['amount'];

        $generalStatistic->save();
        */

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        $updating = function (&$eloquent, $updateData) use ($updating) {
            if (isset($updateData['date'])) {
                $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
            }

            foreach ($eloquent->get() as $teacherFinancial) {
                if (array_key_exists('receipt_image', $updateData) && is_file($teacherFinancial->receipt_image)) {
                    unlink($teacherFinancial->receipt_image);
                }
            }
            if (is_callable($updating)) {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }

    public function delete(callable $deleting = null): mixed
    {
        $deleting = function (&$eloquent) use ($deleting) {
            foreach ($eloquent->get() as $teacherFinancial) {
                /*
                $teacherFinancialGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $teacherFinancial->educational_year);

                $teacherFinancialGeneralStatistic->paid -= $teacherFinancial->amount;

                $teacherFinancialGeneralStatistic->save();
                */
            }

            if (is_callable($deleting)) {
                $deleting($eloquent);
            }
        };

        return parent::delete($deleting);
    }
}
