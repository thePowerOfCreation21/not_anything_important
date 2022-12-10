<?php

namespace App\Http\Controllers;

use App\Actions\FinancialTypeAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinancialTypeController extends Controller
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
            'data' => (new FinancialTypeAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
