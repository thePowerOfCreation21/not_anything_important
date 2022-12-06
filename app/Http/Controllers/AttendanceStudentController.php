<?php

namespace App\Http\Controllers;

use App\Actions\AttendanceStudentAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceStudentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
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
