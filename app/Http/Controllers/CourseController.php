<?php

namespace App\Http\Controllers;

use App\Actions\CourseAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new CourseAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new CourseAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
        ]);
    }

    public function deleteById (string $id): JsonResponse
    {
        (new CourseAction())->deleteById($id);

        return response()->json([
            'message' => 'deleted'
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
            (new CourseAction())
                ->setRequest($request)
                ->setValidationRule('get_query')
                ->makeEloquentViaRequest()
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
            (new CourseAction())->getById($id)
        );
    }
}
