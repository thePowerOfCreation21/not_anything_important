<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdviceDateModel;
use App\Actions\AdviceDateAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class AdviceDateController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AdviceDateAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
