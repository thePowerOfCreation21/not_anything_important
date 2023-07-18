<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\AttendanceResource;
use App\Models\AttendanceModel;
use App\Models\AttendanceStudentModel;
use App\Models\ClassCourseModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Genocide\Radiocrud\Services\SendSMSService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Morilog\Jalali\CalendarUtils;

class AttendanceAction extends ActionService
{
    public function __construct()
    {
        $allowedAttendanceStudentStatusesString = implode(',', $this->getAllowedAttendanceStudentStatuses());

        $this
            ->setModel(AttendanceModel::class)
            ->setResource(AttendanceResource::class)
            ->setValidationRules([
                'storeByAdmin' => [
                    'class_course_id' => ['required', 'integer',],
                    'date' => ['required', 'string'],
                    'description' => ['string', 'max:2500'],
                    'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'integer'],
                    'students.*.status' => ['required', "in:$allowedAttendanceStudentStatusesString"],
                    'students.*.late' => ['nullable', 'integer', 'min:0', 'max:100']
                ],
                'updateByAdmin' => [
                    'class_course_id' => ['string', 'max:20'],
                    'date' => ['string', 'max:2500'],
                    'description' => ['string', 'max:2500'],
                    'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.status' => ["in:$allowedAttendanceStudentStatusesString"],
                    'students.*.late' => ['integer', 'min:0', 'max:100']
                ],
                'getQuery' => [
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                    'date_timestamp' => ['integer'],
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'educational_year' => function (&$eloquent, $query) {
                    if ($query['educational_year']  != '*') {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
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
                        $q->where('teacher_id', $query['teacher_id']);
                    });
                },
                'student_id' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereHas('attendanceStudents', function ($q) use ($query) {
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
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
            ])
            ->setOrderBy(['date' => 'DESC', 'id' => 'DESC']);

        parent::__construct();
    }

    /**
     * @return string[]
     */
    public function getAllowedAttendanceStudentStatuses(): array
    {
        return ['present', 'absent', 'late'];
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = isset($data['date']) ? PardisanHelper::getEducationalYearByGregorianDate($data['date']) : PardisanHelper::getCurrentEducationalYear();
        $data['students'] = $data['students'] ?? [];

        $classCourse = ClassCourseModel::query()->where('id', $data['class_course_id'])->firstOrFail();
        $class = ClassModel::query()->where('id', $classCourse->class_id)->firstOrFail();

        $attendance = parent::store($data, $storing);

        $currentDate = date('Y-m-d');

        $data['jalali_date'] = CalendarUtils::strftime('Y/m/d', strtotime($data['date']));

        $attendanceStudentsHashMap = [];

        $smsStudentIds = []; // list of id of students we may send sms to them

        foreach ($data['students'] as $attendanceStudent) {
            $attendanceStudentsHashMap[$attendanceStudent['student_id']] = [
                'student_id' => $attendanceStudent['student_id'],
                'status' => $attendanceStudent['status'],
                'late' => $attendanceStudent['late'] ?? 0,
                'attendance_id' => $attendance->id
            ];

            if ($attendanceStudent['status'] != 'present') {
                $smsStudentIds[] = $attendanceStudent['student_id'];
            }
        }

        $classStudents = DB::table('class_student')->where('class_id', $class->id)->get();

        foreach ($classStudents as $classStudent) {
            $attendanceStudentsHashMap[$classStudent->student_id] ??= [
                'attendance_id' => $attendance->id,
                'student_id' => $classStudent->student_id,
                'status' => 'present',
                'late' => 0
            ];
        }

        $smsStudents = StudentModel::query()
            ->whereIn('id', $smsStudentIds)
            ->where('last_time_sms_sent', '!=', $currentDate)
            ->get(); // list of students we will send sms to them

        foreach ($smsStudents as $smsStudent) {
            if ($attendanceStudentsHashMap[$smsStudent->id]['status'] == 'absent') {
                (new SendSMSService())->sendOTP([$smsStudent->father_mobile_number, $smsStudent->mother_mobile_number], 'studentAbsent', $smsStudent->full_name ?? 'unknown', $data['jalali_date']);
            } else {
                (new SendSMSService())->sendOTP([$smsStudent->father_mobile_number, $smsStudent->mother_mobile_number], 'studentLate', $smsStudent->full_name ?? 'unknown', $data['jalali_date']);
            }
        }

        StudentModel::query()
            ->whereIn('id', $smsStudentIds)
            ->update([
                'last_time_sms_sent' => $currentDate
            ]);

        AttendanceStudentModel::insert(array_values($attendanceStudentsHashMap));

        return $attendance;
    }

    /**
     * @param string $id
     * @param callable|null $updating
     * @return bool|int
     * @throws CustomException
     */
    public function updateByIdAndRequest(string $id, callable $updating = null): bool|int
    {
        $updating = function ($eloquent, $updateData) use ($updating, $id) {
            foreach ($updateData['students'] ?? [] as $attendanceStudent) {
                DB::table('attendance_student')
                    ->where('attendance_id', $id)
                    ->where('student_id', $attendanceStudent['student_id'])
                    ->update($attendanceStudent);
            }

            if (is_callable($updating)) {
                $updating($eloquent, $updateData);
            }
        };

        return parent::updateByIdAndRequest($id, $updating);
    }

    /**
     * @return array
     */
    public function getAndCastForTeacher(): array
    {
        $attendances = $this->eloquent->get();

        $result = [];

        foreach ($attendances as $attendance) {
            $date = explode(' ', $attendance->date)[0];
            $result['date'] = Helpers::getCustomDateCast($date);
            $result[$date]['attendances'] = $attendance;
        }

        return array_values($result);
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
