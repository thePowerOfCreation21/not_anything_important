<?php

namespace App\Http\Controllers;

use App\Actions\AdminAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function register (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AdminAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    public function login (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'logged in successfully',
            'data' => (new AdminAction())
                ->setRequest($request)
                ->setValidationRule('login')
                ->loginByRequest()->plainTextToken
        ]);
    }


}
