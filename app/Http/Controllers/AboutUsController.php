<?php

namespace App\Http\Controllers;

use App\KeyValueConfigs\AboutUs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get (): JsonResponse
    {
        return response()->json(
            AboutUs::get()
        );
    }
}
