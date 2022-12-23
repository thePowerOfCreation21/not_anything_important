<?php

namespace App\Actions;

use App\Http\Resources\AttendanceResource;
use App\Models\AttendanceModel;
use App\Models\AttendanceStudentModel;
use App\Models\StudentModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use Genocide\Radiocrud\Services\SendSMSService;
use Illuminate\Support\Facades\DB;
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
                    'class_course_id' => ['required', 'string', 'max:20'],
                    'date' => ['required', 'date_format:Y-m-d'],
                    'students' => ['required', 'array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.status' => ['required', "in:$allowedAttendanceStudentStatusesString"],
                    'students.*.late' => ['integer', 'min:0', 'max:100']
                ],
                'updateByAdmin' => [
                    'class_course_id' => ['string', 'max:20'],
                    'date' => ['date_format:Y-m-d'],
                    'students' => ['array', 'max:100'],
                    'students.*.student_id' => ['required', 'string', 'max:20'],
                    'students.*.status' => ["in:$allowedAttendanceStudentStatusesString"],
                    'students.*.late' => ['integer', 'min:0', 'max:100']
                ],
                'getQuery' => [
                    'class_course_id' => ['string', 'max:20'],
                    'class_id' => ['string', 'max:20'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
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
     * @return string[]
     */
    public function getAllowedAttendanceStudentStatuses (): array
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
        $attendance = parent::store($data, $storing);

        $currentDate = date('Y-m-d');

        $data['jalali_date'] = CalendarUtils::strftime('Y/m/d', strtotime($data['date']));

        $attendanceStudentsHashMap = [];

        $smsStudentIds = []; // list of id of students we may send sms to them

        foreach ($data['students'] AS $attendanceStudent)
        {
            $attendanceStudentsHashMap[$attendanceStudent['student_id']] = $attendanceStudent;
            $attendanceStudentsHashMap[$attendanceStudent['student_id']]['attendance_id'] = $attendance->id;

            if ($attendanceStudent['status'] != 'present')
            {
                $smsStudentIds[] = $attendanceStudent['student_id'];
            }
        }

        $smsStudents = StudentModel::query()
            ->whereIn('id', $smsStudentIds)
            ->where('last_time_sms_sent', '!=', $currentDate)
            ->get(); // list of students we will send sms to them

        foreach ($smsStudents AS $smsStudent)
        {
            if ($attendanceStudentsHashMap[$smsStudent->id]['status'] == 'absent')
            {
                (new SendSMSService())->sendOTP([$smsStudent->father_mobile_number, $smsStudent->father_mobile_number], 'studentAbsent', $smsStudent->name ?? 'unknown', $data['jalali_date']);
            }
            else
            {
                (new SendSMSService())->sendOTP([$smsStudent->father_mobile_number, $smsStudent->father_mobile_number], 'studentLate', $smsStudent->name ?? 'unknown', $data['jalali_date']);
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
        $updating = function ($eloquent, $updateData) use ($updating, $id)
        {
            foreach ($updateData['students'] ?? [] AS $attendanceStudent)
            {
                DB::table('attendance_student')
                    ->where('attendance_id', $id)
                    ->where('student_id', $updateData['student_id'])
                    ->update($attendanceStudent);
            }

            if (is_callable($updating))
            {
                $updating($eloquent, $updateData);
            }
        };

        return parent::updateByIdAndRequest($id, $updating);
    }
}
