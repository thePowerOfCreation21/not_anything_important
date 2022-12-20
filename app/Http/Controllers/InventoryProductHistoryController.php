<?php

namespace App\Http\Controllers;

use App\Actions\InventoryProductHistoryAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryProductHistoryController extends Controller
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
            'data' => (new InventoryProductHistoryAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new InventoryProductHistoryAction())->deleteById($id)
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
            (new InventoryProductHistoryAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['inventoryProduct'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
