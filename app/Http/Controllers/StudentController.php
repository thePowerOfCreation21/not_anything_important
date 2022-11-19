<?php

namespace App\Http\Controllers;

use App\Actions\StudentAction;
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
}
