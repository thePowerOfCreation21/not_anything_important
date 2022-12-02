<?php

namespace App\Http\Controllers;

use App\Actions\ClassCourseAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassCourseController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassCourseAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassCourseAction())->deleteById($id)
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
            (new ClassCourseAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classModel', 'course', 'teacher'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
