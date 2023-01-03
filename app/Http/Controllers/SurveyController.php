<?php

namespace App\Http\Controllers;

use App\Actions\SurveyAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
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
            'data' => (new SurveyAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new SurveyAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['surveyOptions'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
