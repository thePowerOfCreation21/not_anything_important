<?php

namespace App\Http\Controllers;

use App\Actions\ClassMessagesAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassMessagesController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassMessagesAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
