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
                    'course_id' => ['integer'],
                    'class_id' => ['string', 'max:20'],
                    'student_id' => ['string', 'max:20'],
                    'date_timestamp' => ['integer'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ],
                'getByStudent' => [
                    'class_score_id' => ['string', 'max:20'],
                    'class_course_id' => ['string', 'max:20'],
                    'course_id' => ['integer'],
                    'class_id' => ['string', 'max:20'],
                    'date_timestamp' => ['integer'],
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
                    $eloquent = $eloquent->whereHas('classScore', function($q) use($query){
                        $q->whereHas('classCourse', function ($q) use($query){
                            $q->where('class_course.class_id', $query['class_id']);
                        });
                    });
                },
                'class_course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classScore', function($q) use($query){
                        $q->where('class_score.class_course_id', $query['class_course_id']);
                    });
                },
                'course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classScore', function($q) use($query){
                        $q->whereHas('classCourse', function ($q) use($query){
                            $q->where('class_course.course_id', $query['course_id']);
                        });
                    });
                },
                'date_timestamp' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classScore', function ($q) use($query){
                        $q->whereDate('date', date('Y-m-d', $query['date_timestamp']));
                    });
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classScore', function($q) use($query){
                        $q->whereDate('date', '>=', $query['from_date']);
                    });
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classScore', function($q) use($query){
                        $q->whereDate('date', '<=', $query['to_date']);
                    });
                },
            ]);

        parent::__construct();
    }

    /**
     * @return array
     */
    public function groupByScoreByEloquent (): array
    {
        $result = [];

        foreach ($this->startEloquentIfIsNull()->eloquent->get() AS $classScoreStudent)
            $result[isset($classScoreStudent?->classScore?->classCourse?->course) ? $classScoreStudent->classScore->classCourse->course->title : 'unknown'][] = $this->applyResourceToEntity($classScoreStudent);

        return $result;
    }
}
