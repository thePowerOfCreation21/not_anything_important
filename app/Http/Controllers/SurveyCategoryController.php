<?php

namespace App\Http\Controllers;

use App\Actions\SurveyCategoryAction;
use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyCategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'success',
            'data' => (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'success',
            'data' => (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
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
            (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('get')
                ->setRelations(['surveys'])
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
            (new SurveyCategoryAction())
                ->setRelations(['surveys.surveyOptions.surveyAnswers.participant'])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->setRelations(['surveys'])
                ->mergeQueryWith([
                    'type' => 'student',
                    'is_active' => true,
                    'has_answered' => false,
                    'educational_year' => PardisanHelper::getCurrentEducationalYear(),
                    'student_id' => $request->user()->id
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByStudent (string $id): JsonResponse
    {
        return response()->json(
            (new SurveyCategoryAction())
                ->setRelations(['surveys.surveyOptions'])
                ->mergeQueryWith(['type' => 'student'])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new SurveyCategoryAction())
                ->setRequest($request)
                ->setValidationRule('getByTeacher')
                ->setRelations(['surveys'])
                ->mergeQueryWith([
                    'type' => 'teacher',
                    'is_active' => true,
                    'has_answered' => false
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByTeacher (string $id): JsonResponse
    {
        return response()->json(
            (new SurveyCategoryAction())
                ->setRelations(['surveys.surveyOptions'])
                ->mergeQueryWith(['type' => 'teacher'])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
