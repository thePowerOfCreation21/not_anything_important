<?php

namespace App\Http\Controllers;

use App\Actions\ClassAction;
use App\Actions\StudentFinancialAction;
use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function storeByAdmin(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    public function get(Request $request): JsonResponse
    {
        return response()->json(
            (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    public function getById(string $id): JsonResponse
    {
        return response()->json(
            (new ClassAction())->getById($id)
        );
    }

    public function updateById(string $classId, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->updateByIdAndRequest($classId)
        ]);
    }

    public function deleteById(string $id): JsonResponse
    {
        (new ClassAction())->deleteById($id);

        return response()->json([
            'message' => 'Class is Deleted'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function addCoursesToClass (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('addCoursesToClass')
                ->addCoursesToClassByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function deleteCourseFromClass (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('deleteCourseFromClass')
                ->deleteCourseFromClassByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function addStudentsToClass (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('addStudentsToClass')
                ->addStudentsToClassByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function deleteStudentFromClass (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassAction())
                ->setRequest($request)
                ->setValidationRule('deleteStudentFromClass')
                ->deleteStudentFromClassByRequest()
        ]);
    }
}
