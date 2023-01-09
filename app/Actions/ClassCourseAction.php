<?php

namespace App\Actions;

use App\Http\Resources\ClassCourseResource;
use App\Models\ClassCourseModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassCourseAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassCourseModel::class)
            ->setValidationRules([
                'store' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'teacher_id' => ['required', 'string', 'max:20'],
                    'course_id' => ['required', 'string', 'max:20'],
                ],
                'update' => [
                    'class_id' => ['string', 'max:20'],
                    'teacher_id' => ['string', 'max:20'],
                    'course_id' => ['string', 'max:20'],
                ],
                'getQuery' => [
                    'class_id' => ['string', 'max:20'],
                    'teacher_id' => ['string', 'max:20'],
                    'course_id' => ['string', 'max:20'],
                ]
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('class_id', $query['class_id']);
                },
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('course_id', $query['course_id']);
                },
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->forStudent($query['student_id']);
                }
            ])
            ->setResource(ClassCourseResource::class);

        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function store(array $data, callable $storing = null): mixed
    {
        if (
            ClassCourseModel::query()
                ->where('class_id', $data['class_id'])
                ->where('teacher_id', $data['teacher_id'])
                ->where('course_id', $data['course_id'])
                ->exists()
        )
        {
            throw new CustomException('this teacher already has this course in this class', 2101, 400);
        }

        return parent::store($data, $storing);
    }
}
