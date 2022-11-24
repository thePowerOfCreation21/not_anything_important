<?php

namespace App\Http\Controllers;

use App\Helpers\PardisanHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EducationalYearController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get (): JsonResponse
    {
        return response()->json(
            range(1400, PardisanHelper::getCurrentEducationalYear() + 1)
        );
    }
}
