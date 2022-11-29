<?php

namespace App\Http\Controllers;

use App\Actions\ClassAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function storeByAdmin(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    public function deleteById(string $id): JsonResponse
    {
        (new ClassAction())->deleteById($id);

        return response()->json([
            'message' => 'Class is Deleted'
        ]);
    }
}
