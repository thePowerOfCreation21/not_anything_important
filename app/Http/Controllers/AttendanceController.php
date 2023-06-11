<?php

namespace App\Http\Controllers;

use App\Actions\AttendanceAction;
use App\Helpers\PardisanHelper;
use App\Http\Resources\GroupByDateResource;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
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
    public function getGroupByDate (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getById (string $id): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRelations(['classCourse.classModel', 'attendanceStudents.student'])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AttendanceAction())->deleteById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
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
    public function getByIdByTeacher (string $id, Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRelations(['classCourse.classModel', 'attendanceStudents.student'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->makeEloquent()
                ->getById($id)
        );
    }

    public function getGroupByDateByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }

    public function getGroupByDateByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new AttendanceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }
}
