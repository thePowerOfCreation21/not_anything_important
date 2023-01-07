<?php

namespace App\Http\Controllers;

use App\Actions\ClassAction;
use App\Actions\ClassReportsAction;
use App\Actions\CourseAction;
use App\Actions\TeacherAction;
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
                ->setRelations(['classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
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
                ->setRelations(['classCourse.classModel'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
