<?php

namespace App\Actions;

use App\Http\Resources\AttendanceStudentResource;
use App\Models\AttendanceStudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class AttendanceStudentAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AttendanceStudentModel::class)
            ->setValidationRules([
                'getQuery' => [
                    'attendance_id' => ['string', 'max:20'],
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
                'attendance_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('attendance_id', $query['attendance_id']);
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
            ])
            ->setResource(AttendanceStudentResource::class);

        parent::__construct();
    }
}
