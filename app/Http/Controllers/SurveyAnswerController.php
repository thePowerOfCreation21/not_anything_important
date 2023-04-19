<?php

namespace App\Http\Controllers;

use App\Actions\SurveyAnswerAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyAnswerController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     * @throws \Throwable
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new SurveyAnswerAction())
                ->setRequest($request)
                ->storeAnswerByRequest()
        ]);
    }
}
