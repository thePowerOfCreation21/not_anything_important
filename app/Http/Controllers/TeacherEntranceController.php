<?php

namespace App\Http\Controllers;

use App\Actions\TeacherEntranceAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherEntranceController extends Controller
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
            'data' => (new TeacherEntranceAction())
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
            'data' => (new TeacherEntranceAction())
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
            (new TeacherEntranceAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['teacher'])
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
            (new TeacherEntranceAction())
                ->setRelations(['teacher'])
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
            'data' => (new TeacherEntranceAction())->deleteById($id)
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
            (new TeacherEntranceAction())
                ->setRequest($request)
                ->setValidationRule('getByTeacher')
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
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
            (new TeacherEntranceAction())
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
