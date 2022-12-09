<?php

namespace App\Http\Controllers;

use App\Actions\ClassFileAction;
use App\Helpers\PardisanHelper;
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
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    public function deleteById (string $id)
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassFileAction())->deleteById($id)
        ]);
    }
}
