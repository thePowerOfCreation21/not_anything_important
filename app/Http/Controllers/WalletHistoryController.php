<?php

namespace App\Http\Controllers;

use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Actions\WalletHistoryAction;

class WalletHistoryController extends Controller
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
            'data' => (new WalletHistoryAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByAdminViaRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json(
            (new WalletHistoryAction())
            ->setRequest($request)
            ->setValidationRule('getQuery')
            ->setRelations(['student'])
            ->mergeQueryWithQueryFromRequest()
            ->makeEloquent()
            ->getByRequestAndEloquent()
        );
    }
}
