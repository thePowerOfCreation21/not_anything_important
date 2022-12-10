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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request) : JsonResponse
    {
        return response()->json(
            (new FinancialTypeAction())
                ->setRequest($request)
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getById(string $id): JsonResponse
    {
        return response()->json(
            (new FinancialTypeAction())
                ->getById($id)
        );
    }
}
