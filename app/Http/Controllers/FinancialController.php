<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financial;
use App\Actions\FinancialAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class FinancialController extends Controller
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
            'data' => (new FinancialAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
