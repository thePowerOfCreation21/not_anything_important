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
use Genocide\Radiocrud\Services\SendSMSService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
                    'date' => ['required', 'string'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:1,999.99'],
                    // 'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:0,999.99'],
                ],
                'storeByTeacher' => [
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'date' => ['required', 'string'],
                    'educational_year' => ['nullable', 'string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:0,999.99'],
                    // 'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['required', 'numeric', 'between:0,999.99'],
                ],
                'updateByAdmin' => [
                    // 'class_course_id' => ['string', 'max:20'],
                    'date' => ['string'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:0,999.99'],
                    // 'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['numeric', 'between:0,999.99'],
                ],
                'updateByTeacher' => [
                    // 'class_course_id' => ['string', 'max:20'],
                    'date' => ['string'],
                    'educational_year' => ['string', 'min:2', 'max:50'],
                    'max_score' => ['numeric', 'between:0,999.99'],
                    // 'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.score' => ['numeric', 'between:0,999.99'],
                ],
                'getQuery' => [
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'date_timestamp' => ['integer'],
                ]
            ])->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d H:i'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'class_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use ($query) {
                        $q->where('class_id', $query['class_id']);
                    });
                },
                'class_course_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->where('class_course_id', $query['class_course_id']);
                },
                'teacher_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classCourse', function ($q) use ($query) {
                        $q->where('class_course.teacher_id', $query['teacher_id']);
                    });
                },
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('classScoreStudents', function ($q) use ($query) {
                        $q->where('student_id', $query['student_id']);
                    });
                },
                'date_timestamp' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->wheredate('date', date('Y-m-d', $query['date_timestamp']));
                },
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
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
    public function getSubmitterByRequest(): array
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
     * @throws CustomException|Throwable
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

        $classCourse = ClassCourseModel::query()
            ->where('id', $data['class_course_id'])
            ->with('course')
            ->firstOrFail();

        throw_if(
            is_a($data['submitter_type'], TeacherModel::class) && ($classCourse->teacher_id != $data['submitter_id']),
            CustomException::class,
            'class_course_id is wrong',
            '47562',
            '400'
        );

        $classScore = parent::store($data, $storing);

        $classScoreStudents = [];
        $classScoreStudentsHashMap = [];
        $studentIds = [];

        foreach ($data['students'] as $classScoreStudent) {
            $classScoreStudentsHashMap[$classScoreStudent['student_id']] = $classScoreStudent;
            $classScoreStudentsHashMap[$classScoreStudent['student_id']]['class_score_id'] = $classScore->id;
            $studentIds[] = $classScoreStudent['student_id'];
        }

        foreach (StudentModel::query()->whereIn('id', $studentIds)->get() as $student) {

            if (!empty($classCourse->course))
            {
                $phoneNumbers = [];
                if(!empty($student->father_mobile_number))
                    $phoneNumbers[] = $student->father_mobile_number;
                if(!empty($student->mother_mobile_number))
                    $phoneNumbers[] = $student->mother_mobile_number;

                if(!empty($phoneNumbers))
                    (new SendSMSService())
                        ->sendOTP(
                            $phoneNumbers,
                            'studentScore',
                            $student->full_name,
                            $classCourse->course->title,
                            $classScoreStudentsHashMap[$student->id]['score'],
                            $data['max_score'] ?? '20'
                        );
            }

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
        $updating = function ($eloquent, $updateData) use ($updating) {
            foreach ($eloquent->get() as $classScore) {
                foreach ($updateData['students'] ?? [] as $classScoreStudent) {
                    ClassScoreStudentModel::query()
                        ->where('class_score_id', $classScore->id)
                        ->where('student_id', $classScoreStudent['student_id'])
                        ->update($classScoreStudent);
                }
            }

            if (is_callable($updating)) {
                $updating($eloquent, $updateData);
            }
        };

        return parent::update($updateData, $updating);
    }

    /**
     * @return $this
     */
    public function groupByDate(): static
    {
        $this->eloquent = $this
            ->eloquent
            ->select(
                DB::raw("DATE_FORMAT(`date`, '%Y-%m-%d 00:00:00') AS `date`"),
                DB::raw("count(`id`) as `count`")
            )
            ->groupBy(DB::raw("DATE_FORMAT(`date`, '%Y-%m-%d 00:00:00')"));

        $sql = $this->eloquent->toSql();

        $bindings = array_map(
            fn ($parameter) => is_string($parameter) ? "'$parameter'" : $parameter,
            $this->eloquent->getBindings()
        );

        $sql = Str::replaceArray('?', $bindings, $sql);

        $this->eloquent = DB::table(DB::raw("($sql) AS sub"));

        return $this;
    }
}
