<?php

namespace App\Http\Controllers;

use App\Actions\StudentAction;
use App\Helpers\PardisanHelper;
use App\Http\Resources\StudentCollectionResource;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
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
            'data' => (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->setDefaultRegisterStatus('added_by_admin')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $studentId
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateById (string $studentId, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->updateByIdAndRequest($studentId)
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
            (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(StudentCollectionResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
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
            (new StudentAction())->getById($id)
        );
    }

    /**
     * @param string $studentId
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function block (string $studentId, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('block')
                ->blockByRequest($studentId)
        ]);
    }

    /**
     * @param string $studentId
     * @return JsonResponse
     */
    public function unblock (string $studentId): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentAction())
                ->unblock($studentId)
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function acceptById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'date' => (new StudentAction())->acceptById($id)
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function rejectById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'date' => (new StudentAction())->rejectById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function registerRequest (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('registerRequest')
                ->storeByRequest()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function login (Request $request): JsonResponse
    {
        return response()->json(
            (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('login')
                ->loginByRequest()
        );
    }
}
