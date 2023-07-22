<?php

namespace App\Http\Controllers;

use App\Actions\ContactUsMessageAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactUsMessageController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'stored successfully',
            'data' => (new ContactUsMessageAction())
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
            (new ContactUsMessageAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getById (string $id): JsonResponse
    {
        return response()->json(
            (new ContactUsMessageAction())
                ->getById($id)
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'deleted successfully',
            'data' => (new ContactUsMessageAction())
                ->deleteById($id)
        ]);
    }
}
