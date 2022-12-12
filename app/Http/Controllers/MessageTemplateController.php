<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageTemplate;
use App\Actions\MessageTemplateAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class MessageTemplateController extends Controller
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
            'data' => (new MessageTemplateAction())
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
    public function get(Request $request): JsonResponse
    {
        return response()->json(
            (new MessageTemplateAction())
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
            (new MessageTemplateAction())
                ->getById($id)
        );
    }
}
