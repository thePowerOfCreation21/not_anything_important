<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\ClassScoreResource;
use App\Models\ClassScoreModel;
use App\Models\ClassScoreStudentModel;
use App\Models\StudentModel;
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
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:1,999.99'],
                ],
                'updateByAdmin' => [
                    'class_course_id' => ['string', 'max:20'],
                    'date' => ['date_format:Y-m-d'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:1,999.99'],
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

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        $classScore = parent::store($data, $storing);

        $classScoreStudents = [];
        $classScoreStudentsHashMap = [];
        $studentIds = [];

        foreach ($data['students'] AS $classScoreStudent)
        {
            $classScoreStudentsHashMap[$classScoreStudent['student_id']] = $classScoreStudent;
            $classScoreStudentsHashMap[$classScoreStudent['student_id']]['class_score_id'] = $classScore->id;
            $studentIds[] = $classScoreStudent['student_id'];
        }

        foreach (StudentModel::query()->whereIn('id', $studentIds)->get() AS $student)
        {
            // TODO: send sms to student's parent
            $classScoreStudents[] = $classScoreStudentsHashMap[$student->id];
        }

        ClassScoreStudentModel::insert($classScoreStudents);

        return $classScore;
    }
}
