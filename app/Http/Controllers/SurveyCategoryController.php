<?php

namespace App\Http\Controllers;

use App\Actions\SurveyCategoryAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyCategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'success',
            'data' => (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
