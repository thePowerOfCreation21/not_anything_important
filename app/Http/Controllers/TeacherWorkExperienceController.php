<?php

namespace App\Http\Controllers;

use App\Actions\TeacherWorkExperienceAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherWorkExperienceController extends Controller
{
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new TeacherWorkExperienceAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new TeacherWorkExperienceAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
        ]);
    }

    public function deleteById (string $id): JsonResponse
    {
        (new TeacherWorkExperienceAction())->deleteById($id);

        return response()->json([
            'message' => 'deleted'
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
            (new TeacherWorkExperienceAction())
                ->setRequest($request)
                ->setValidationRule('get_query')
                ->makeEloquentViaRequest()
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
            (new TeacherWorkExperienceAction())->getById($id)
        );
    }
}
