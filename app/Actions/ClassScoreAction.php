<?php

namespace App\Actions;

use App\Http\Resources\ClassScoreResource;
use App\Models\ClassScoreModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassScoreAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassScoreModel::class)
            ->setResource(ClassScoreResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'date' => ['required', 'date_format:Y-m-d'],
                    'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['integer', 'min:0', 'max:100'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99']
                ],
                'updateByAdmin' => [
                    'class_course_id' => ['string', 'max:20'],
                    'date' => ['date_format:Y-m-d'],
                    'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['integer', 'min:0', 'max:100'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99']
                ],
                'getQuery' => [
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classCourse', function($q) use($query){
                        $q->where('class_id', $query['class_id']);
                    });
                },
                'class_course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_course_id', $query['class_course_id']);
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['from_date']);
                },
            ])
            ->setOrderBy(['date' => 'DESC', 'id' => 'DESC']);
        parent::__construct();
    }


}
