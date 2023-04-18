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
}
