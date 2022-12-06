<?php

namespace App\Http\Controllers;

use App\Actions\TeacherAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
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
            'data' => (new TeacherAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->setDefaultRegisterStatus('added_by_admin')
                ->storeByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function registerRequest (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new TeacherAction())
                ->setRequest($request)
                ->setValidationRule('store')
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
            'data' => (new TeacherAction())
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
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new TeacherAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
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
            (new TeacherAction())
                ->setRelations(['workExperiences', 'skills'])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function acceptById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'date' => (new TeacherAction())->acceptById($id)
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function rejectById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'date' => (new TeacherAction())->rejectById($id)
        ]);
    }
}
