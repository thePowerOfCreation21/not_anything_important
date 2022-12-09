<?php

namespace App\Http\Controllers;

use App\Actions\ClassFileAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassFileController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassFileAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByAdminByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByAdmin (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassFileAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations([
                    'author',
                    'classModel',
                    'classCourse' => ['course', 'classModel']
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
