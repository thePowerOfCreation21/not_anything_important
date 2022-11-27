<?php

namespace App\Actions;
use App\Http\Resources\StudentDisciplineResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Helpers\PardisanHelper;
use App\Models\StudentDisciplineModel;

class StudentDisciplineAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentDisciplineModel::class)
            ->setResource(StudentDisciplineResource::class)
            ->setValidationRules([
                'store' => [
                    'student_id' => ['required', 'string', 'max:20'],
                    'title' => ['required', 'string', 'max:250'],
                    'description' => ['required', 'string', 'max:500'],
                    'date' => ['required', 'date_format:Y-m-d']
                ],
                'getQuery' => [
                    'student_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('date', '<=', $query['to_date']);
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

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }
}
