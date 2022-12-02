<?php

namespace App\Http\Controllers;

use App\Actions\ClassAction;
use App\Actions\ClassReportsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassReportsController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassReportsAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(
            (new ClassReportsAction())->getById($id)
        );
    }
}
