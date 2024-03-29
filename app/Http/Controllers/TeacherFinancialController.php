<?php

namespace App\Http\Controllers;

use App\Actions\TeacherFinancialAction;
use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\TeacherFinancialModel;

class TeacherFinancialController extends Controller
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
            'data' => (new TeacherFinancialAction())
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
            (new TeacherFinancialAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
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
    public function getById(string $id): JsonResponse
    {
        return response()->json(
            (new TeacherFinancialAction())->getById($id)
        );
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateById(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new TeacherFinancialAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById(string $id): JsonResponse
    {
        return response()->json([
            'message' => 'deleted',
            'data' => (new TeacherFinancialAction())
                ->deleteById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher(Request $request): JsonResponse
    {
        return response()->json(
            (new TeacherFinancialAction())
                ->setRequest($request)
                ->setValidationRule('getByTeacher')
                ->mergeQueryWith([
                    'educational_year' => PardisanHelper::getCurrentEducationalYear(),
                    'teacher_id' => $request->user()->id
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
