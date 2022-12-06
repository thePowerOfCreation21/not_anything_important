<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ClassScoreStudentModel;
use App\Http\Resources\ClassScoreStudentResource;

class ClassScoreStudentAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassScoreStudentModel::class)
            ->setResource(ClassScoreStudentResource::class)
            ->setValidationRules([
                'getQuery' => [
                    'class_score_id' => ['string', 'max:20'],
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'student_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'class_score_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_score_id', $query['class_score_id']);
                },
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('student_id', $query['student_id']);
                },
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('attendance', function($q) use($query){
                        $q->whereHas('classCourse', function ($q) use($query){
                            $q->where('class_id', $query['class_id']);
                        });
                    });
                },
                'class_course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('attendance', function($q) use($query){
                        $q->where('class_course_id', $query['class_course_id']);
                    });
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('attendance', function($q) use($query){
                        $q->whereDate('date', '>=', $query['from_date']);
                    });
                },
            ]);

        parent::__construct();
    }
}
