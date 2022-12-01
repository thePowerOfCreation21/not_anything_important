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
                    'date' => ['required', 'date_format:Y-m-d']
                ],
                'update' => [
                    'amount' => ['int', 'min:0', 'max:100000000'],
                    'date' => ['date_format:Y-m-d']
                ],
                'getQuery' => [
                    'teacher_id' => ['string', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ])
            ->setQueryToEloquentClosures([
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
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

        /*
        $generalStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $data['educational_year']);

        $generalStatistic->paid += $data['amount'];

        $generalStatistic->save();
        */

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        $updating = function (&$eloquent, $updateData) use ($updating)
        {
            if(isset($updateData['date']))
            {
                $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
            }

            foreach ($eloquent->get() AS $teacherFinancial)
            {
                /*
                $teacherFinancialGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $teacherFinancial->educational_year);

                $teacherFinancialGeneralStatistic->paid -= $teacherFinancial->amount;

                $teacherFinancialGeneralStatistic->save();

                $newGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $updateData['educational_year'] ?? $teacherFinancial->educational_year);

                $newGeneralStatistic->paid += $updateData['amount'] ?? $teacherFinancial->amount;

                $newGeneralStatistic->save();
                */
            }
            if(is_callable($updating))
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
            foreach ($eloquent->get() AS $teacherFinancial)
            {
                /*
                $teacherFinancialGeneralStatistic = (new GeneralStatisticAction())->getFirstByLabelAndEducationalYearOrCreate('teacher_financial', $teacherFinancial->educational_year);

                $teacherFinancialGeneralStatistic->paid -= $teacherFinancial->amount;

                $teacherFinancialGeneralStatistic->save();
                */
            }

            if(is_callable($deleting))
            {
                $deleting($eloquent);
            }
        };

        return parent::delete($deleting);
    }
}
