<?php

namespace App\Http\Controllers;

use App\Actions\AttendanceStudentAction;
use Illuminate\Http\Request;

class AttendanceStudentController extends Controller
{
    public function get (Request $request)
    {
        return response()->json(
            (new AttendanceStudentAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['attendance.classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
