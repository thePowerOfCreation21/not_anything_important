<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageModel;
use App\Actions\MessageAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class MessageController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new MessageAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
