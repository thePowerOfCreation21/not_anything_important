<?php

namespace App\Http\Controllers;

use App\Actions\StudentReportCardAction;
use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentReportCardController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new StudentReportCardAction())
                ->setRequest($request)
                ->setRelations(['classModel'])
                ->setValidationRule('get')
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
            (new StudentReportCardAction())
                ->setRelations(['classModel', 'studentReportCardScores.course'])
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
            (new StudentReportCardAction())
                ->setRequest($request)
                ->setRelations(['classModel'])
                ->setValidationRule('getByStudent')
                ->mergeQueryWith([
                    'educational_year' => PardisanHelper::getCurrentEducationalYear(),
                    'student_id' => $request->user()->id
                ])
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
            (new StudentReportCardAction())
                ->setRelations(['classModel', 'studentReportCardScores.course'])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
