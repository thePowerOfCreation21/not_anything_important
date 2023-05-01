<?php

namespace App\Http\Controllers;

use App\KeyValueConfigs\HomePageContent;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomePageContentController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get (): JsonResponse
    {
        return response()->json(
            HomePageContent::get()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update (Request $request): JsonResponse
    {
        (new HomePageContent())
            ->setRequest($request)
            ->update_by_request();

        return response()->json([
            'message' => 'home page content updated successfully',
        ]);
    }
}
