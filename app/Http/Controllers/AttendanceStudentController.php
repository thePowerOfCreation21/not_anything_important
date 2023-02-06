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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceStudentAction())
                ->setRequest($request)
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations([
                    'attendance' => [
                        'classCourse' => ['teacher', 'course', 'classModel']
                    ],
                    'student'
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
