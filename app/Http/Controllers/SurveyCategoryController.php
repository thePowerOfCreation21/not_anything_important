<?php

namespace App\Http\Controllers;

use App\Actions\SurveyCategoryAction;
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
                ->setValidationRule($request)
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
                ->setRelations(['surveys.surveyOptions.surveyAnswers.student'])
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
                ->mergeQueryWith(['type' => 'student'])
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
                ->mergeQueryWith(['type' => 'teacher'])
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
