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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json(
            (new AdviceHourAction())
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
            (new AdviceHourAction())
                ->getById($id)
        );
    }
}
