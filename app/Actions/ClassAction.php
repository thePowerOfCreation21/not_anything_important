<?php

namespace App\Actions;

use App\Models\AdminModel;
use App\Models\ClassModel;
use App\Http\Resources\ClassResource;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Illuminate\Support\Facades\DB;

class ClassAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassModel::class)
            ->setResource(ClassResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'title' => ['required', 'string', 'min:2', 'max:250'],
                    'level' => ['string', 'min:2', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ],
                'updateByAdmin' => [
                    'title' => ['string', 'min:2', 'max:250'],
                    'level' => ['string', 'min:2', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getQuery' => [
                    'search' => ['string', 'max:150'],
                    'student_id' => ['integer'],
                    'educational_year' => ['string', 'max:50']
                ],
                'getByStudent' => [
                    'search' => ['string', 'max:150'],
                    'educational_year' => ['string', 'max:50']
                ],
                'addCourseToClass' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'courses' => ['required', 'array', 'max:100'],
                    'courses.*' => ['required', 'string', 'max:20']
                ],
                'deleteCourseFromClass' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'course_id' => ['required', 'string', 'max:20'],
                ],
                'addStudentsToClass' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'students' => ['required', 'array', 'max:100'],
                    'students.*' => ['required', 'integer', 'min:1', 'digits_between:1,20']
                ],
                'deleteStudentFromClass' => [
                    'class_id' => ['required', 'string', 'max:20'],
                    'student_id' => ['required', 'string', 'max:20'],
                ],
            ])
            ->setQueryToEloquentClosures([
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'search' => function (&$eloquent, $query)
                {
                    $search = $query['search'];
                    $eloquent = $eloquent->where(function ($q) use ($search){
                      $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('level', 'LIKE', "%{$search}%");
                    });
                },
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('students', function($q) use($query){
                        $q->where('students.id', $query['student_id']);
                    });
                },
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('courses', function($q) use($query){
                        $q->where('class_course.teacher_id', $query['teacher_id']);
                    });
                }
            ]);
        parent::__construct();
    }

    /**
     * @param string $classId
     * @param array $relatedIds
     * @param string $table
     * @param string $relatedPivotForeignKey
     * @return bool
     */
    public function addStuffToClass (string $classId, array $relatedIds, string $table, string $relatedPivotForeignKey): bool
    {
        $hashMap = [];

        foreach (
            DB::table($table)
                ->where('class_id', $classId)
                ->get()
            AS $pivotRecord
        )
        {
            $hashMap[$pivotRecord->$relatedPivotForeignKey] = 1;
        }

        $pivotsToAdd = [];

        foreach ($relatedIds AS $relatedId)
        {
            if (! isset($hashMap[$relatedId]))
            {
                $pivotsToAdd[] = [
                    'class_id' => $classId,
                    $relatedPivotForeignKey => $relatedId
                ];
            }
        }

        return DB::table($table)->insert($pivotsToAdd);
    }

    /**
     * @param string $classId
     * @param string $relatedId
     * @param string $table
     * @param string $relatedPivotForeignKey
     * @return int
     */
    public function deleteStuffFromClass (string $classId, string $relatedId, string $table, string $relatedPivotForeignKey): int
    {
        return DB::table($table)
            ->where('class_id', $classId)
            ->where($relatedPivotForeignKey, $relatedId)
            ->delete();
    }

    /**
     * @param array $data
     * @return int
     */
    public function deleteCourseFromClass (array $data): int
    {
        return $this->deleteStuffFromClass($data['class_id'], $data['course_id'], 'class_course', 'course_id');
    }

    /**
     * @return int
     * @throws CustomException
     */
    public function deleteCourseFromClassByRequest (): int
    {
        return $this->deleteCourseFromClass(
            $this->getDataFromRequest()
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addCoursesToClass (array $data): bool
    {
        return $this->addStuffToClass($data['class_id'], $data['courses'], 'class_course', 'course_id');
    }

    /**
     * @return bool
     * @throws CustomException
     */
    public function addCoursesToClassByRequest (): bool
    {
        return $this->addCoursesToClass(
            $this->getDataFromRequest()
        );
    }

    /**
     * @param array $data
     * @return int
     */
    public function deleteStudentFromClass (array $data): int
    {
        return $this->deleteStuffFromClass($data['class_id'], $data['student_id'], 'class_student', 'student_id');
    }

    /**
     * @return int
     * @throws CustomException
     */
    public function deleteStudentFromClassByRequest (): int
    {
        return $this->deleteStudentFromClass(
            $this->getDataFromRequest()
        );
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addStudentsToClass (array $data): bool
    {
        return $this->addStuffToClass($data['class_id'], $data['students'], 'class_student', 'student_id');
    }

    /**
     * @return bool
     * @throws CustomException
     */
    public function addStudentsToClassByRequest (): bool
    {
        return $this->addStudentsToClass(
            $this->getDataFromRequest()
        );
    }
}
