<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdviceHourModel;
use App\Actions\AdviceHourAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class AdviceHourController extends Controller
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
           'data' => (new AdviceHourAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
