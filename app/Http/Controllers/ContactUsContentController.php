<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\KeyValueConfigs\ContactUsContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactUsContentController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get (): JsonResponse
    {
        return response()->json(
            (new ContactUsContent())->get()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update (Request $request): JsonResponse
    {
        (new ContactUsContent())->update_by_request($request);
        return response()->json([
            'message' => 'updated'
        ]);
    }
}
