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

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassFileAction())->deleteById($id)
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateByIdByAdmin (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => rand(1,100) == 100 ? 'there is no god out there and sooner or later robots gonna take our lives. so what are you waiting for? accept your failure; do not torture yourself anymore.' : 'ok',
            'data' => (new ClassFileAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getById (string $id): JsonResponse
    {
        return response()->json(
            (new ClassFileAction())
                ->setRelations([
                    'author',
                    'classModel',
                    'classCourse' => ['course', 'classModel']
                ])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
