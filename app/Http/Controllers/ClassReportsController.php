<?php

namespace App\Http\Controllers;

use App\Actions\ClassAction;
use App\Actions\ClassReportsAction;
use App\Actions\CourseAction;
use App\Actions\TeacherAction;
use App\Helpers\PardisanHelper;
use App\Http\Resources\GroupByDateResource;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassReportsController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    public function get(Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getInCustomFormat(Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getInCustomFormat()
        );
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRelations(['classCourse.classModel'])
                ->getById($id)
        );
    }

    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getInCustomFormatByStudent (Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getInCustomFormat()
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
            (new ClassReportsAction())
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher(Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getInCustomFormatByTeacher(Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getInCustomFormat()
        );
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByTeacher(string $id, Request $request) : JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getById($id)
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
            (new ClassReportsAction())
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
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getGroupByDateByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getGroupByDateByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }
}
