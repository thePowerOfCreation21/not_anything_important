<?php

namespace App\Http\Controllers;

use Genocide\Radiocrud\Exceptions\CustomException;
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
        (new ContactUsContent())
            ->setRequest($request)
            ->update_by_request();
        return response()->json([
            'message' => 'updated'
        ]);
    }
}
