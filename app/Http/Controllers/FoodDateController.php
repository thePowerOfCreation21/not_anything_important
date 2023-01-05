<?php

namespace App\Http\Controllers;

use App\Actions\FoodDateAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodDateController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new FoodDateAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
