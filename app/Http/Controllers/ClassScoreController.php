<?php

namespace App\Http\Controllers;

use App\Actions\ClassScoreAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassScoreController extends Controller
{
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }
}
