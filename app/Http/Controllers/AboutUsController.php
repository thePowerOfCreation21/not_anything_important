<?php

namespace App\Http\Controllers;

use App\KeyValueConfigs\AboutUs;
use Genocide\Radiocrud\Exceptions\CustomException;
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update (Request $request): JsonResponse
    {
        (new AboutUs())
            ->setRequest($request)
            ->update_by_request();

        return response()->json([
            'message' => 'AboutUs updated successfully',
        ]);
    }
}
