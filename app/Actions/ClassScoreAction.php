<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\ClassScoreResource;
use App\Models\ClassCourseModel;
use App\Models\ClassScoreModel;
use App\Models\ClassScoreStudentModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

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
                    'date' => ['required', 'date_format:Y-m-d H:i'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    // 'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:1,999.99'],
                ],
                'storeByTeacher' => [
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'date' => ['required', 'date_format:Y-m-d H:i'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    // 'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:1,999.99'],
                ],
                'updateByAdmin' => [
                    // 'class_course_id' => ['string', 'max:20'],
                    'date' => ['date_format:Y-m-d H:i'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    // 'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['numeric', 'between:1,999.99'],
                ],
                'updateByTeacher' => [
                    // 'class_course_id' => ['string', 'max:20'],
                    'date' => ['date_format:Y-m-d H:i'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    // 'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['numeric', 'between:1,999.99'],
                ],
                'getQuery' => [
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d H:i'],
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
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use ($query){
                        $q->where('class_course.teacher_id', $query['teacher_id']);
                    });
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
     * @return array
     * @throws CustomException
     */
    #[ArrayShape(['submitter_type' => "string", 'submitter_id' => "mixed"])]
    public function getSubmitterByRequest (): array
    {
        $user = $this->getUserFromRequest();

        return [
            'submitter_type' => get_class($user),
            'submitter_id' => $user->id
        ];
    }

    /**
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     */
    public function storeByRequest(callable $storing = null): mixed
    {
        return $this->store(
            array_merge(
                $this->getDataFromRequest(),
                $this->getSubmitterByRequest()
            ),
            $storing
        );
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getCurrentEducationalYear();

        throw_if(
            is_a($data['submitter_type'], TeacherModel::class) && ! ClassCourseModel::query()
                ->where('id', $data['class_course_id'])
                ->where('teacher_id', $data['submitter_id'])
                ->exists(),
            CustomException::class, 'class_course_id is wrong', '47562', '400'
        );

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

    /**
     * @param callable|null $updating
     * @return bool|int
     * @throws CustomException
     */
    public function updateByRequest(callable $updating = null): bool|int
    {
        return $this->update(
            array_merge(
                $this->getDataFromRequest(),
                $this->getSubmitterByRequest()
            ),
            $updating
        );
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        $updating = function ($eloquent, $updateData) use ($updating)
        {
            foreach ($eloquent->get() AS $classScore)
            {
                foreach ($updateData['students'] ?? [] AS $classScoreStudent)
                {
                    ClassScoreStudentModel::query()
                        ->where('class_score_id', $classScore->id)
                        ->where('student_id', $classScoreStudent['student_id'])
                        ->update($classScoreStudent);
                }
            }

            if (is_callable($updating))
            {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }
}
