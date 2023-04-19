<?php

namespace App\Http\Controllers;

use App\Actions\AttendanceStudentAction;
use App\Helpers\PardisanHelper;
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
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
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
                ->setValidationRule('getByStudent')
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
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

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByStudent (string $id, Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceStudentAction())
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations([
                    'attendance' => [
                        'classCourse' => ['teacher', 'course', 'classModel']
                    ],
                    'student'
                ])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
