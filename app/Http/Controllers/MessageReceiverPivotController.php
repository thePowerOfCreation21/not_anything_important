<?php

namespace App\Http\Controllers;

use App\Actions\MessageReceiverPivotAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageReceiverPivotController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new MessageReceiverPivotAction())
                ->setRequest($request)
                ->setValidationRule('get')
                ->mergeQueryWith([
                    'student_id' => $request->user()->id
                ])
                ->setRelations(['message'])
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
            (new MessageReceiverPivotAction())
                ->mergeQueryWith([
                    'student_id' => $request->user()->id
                ])
                ->setRelations(['message'])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new MessageReceiverPivotAction())
                ->setRequest($request)
                ->setValidationRule('get')
                ->mergeQueryWith([
                    'teacher_id' => $request->user()->id
                ])
                ->setRelations(['message'])
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
            (new MessageReceiverPivotAction())
                ->mergeQueryWith([
                    'teacher_id' => $request->user()->id
                ])
                ->setRelations(['message'])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
