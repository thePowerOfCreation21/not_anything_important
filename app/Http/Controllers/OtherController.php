<?php

namespace App\Http\Controllers;

use App\Helpers\PardisanHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    public function getUserType (Request $request): JsonResponse
    {
        return response()->json([
            'type' => PardisanHelper::getUserTypeByUserClass(get_class($request->user()))
        ]);
    }
}
