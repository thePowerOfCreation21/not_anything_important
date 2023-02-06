<?php

namespace App\Http\Controllers;

use App\KeyValueConfigs\Header;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get (): JsonResponse
    {
        return response()->json(
            Header::get()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update (Request $request): JsonResponse
    {
        (new Header())
            ->setRequest($request)
            ->update_by_request();

        return response()->json([
            'message' => 'header updated successfully',
        ]);
    }
}
