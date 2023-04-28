<?php

namespace App\Http\Controllers;

use App\Actions\GalleryImageAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryImageController extends Controller
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
            'data' => (new GalleryImageAction())
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
            'data' => (new GalleryImageAction())->deleteById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new GalleryImageAction())
                ->setRequest($request)
                ->makeEloquent()
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
            (new GalleryImageAction())->getById($id)
        );
    }
}
