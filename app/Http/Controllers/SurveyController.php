<?php

namespace App\Http\Controllers;

use App\Actions\SurveyAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new SurveyAction())
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
            'message' => 'ok',
            'data' => (new SurveyAction())
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
            (new SurveyAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['surveyOptions'])
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
            (new SurveyAction())
                ->setRelations(['surveyOptions.surveyAnswers.participant'])
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
            (new SurveyAction())
                ->setRequest($request)
                ->setValidationRule('getByTeacher')
                ->setRelations(['surveyOptions'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByTeacher (string $id, Request $request): JsonResponse
    {
        return response()->json(
            (new SurveyAction())
                ->setRelations(['surveyOptions.surveyAnswers.participant'])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
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
            (new SurveyAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->setRelations(['surveyOptions', 'teacher'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByStudent (string $id, Request $request): JsonResponse
    {
        return response()->json(
            (new SurveyAction())
                ->setRelations(['surveyOptions', 'teacher'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
