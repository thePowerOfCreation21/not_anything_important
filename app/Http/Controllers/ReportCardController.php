<?php

namespace App\Http\Controllers;

use App\Actions\ReportCardAction;
use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportCardController extends Controller
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
            'data' => (new ReportCardAction())
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
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new ReportCardAction())
                ->setRequest($request)
                ->setValidationRule('get')
                ->setRelations(['classModel'])
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
            (new ReportCardAction())
                ->setRelations([
                    'classModel',
                    'reportCardExams' => ['course', 'reportCardExamScores.studentModel']
                ])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function issueReportCards (Request $request): JsonResponse
    {
        return response()->json(
            (new ReportCardAction())
                ->setRequest($request)
                ->setValidationRule('issueReportCards')
                ->setRelations([
                    'classModel',
                    'reportCardExams' => ['course', 'reportCardExamScores']
                ])
                ->mergeQueryWith(['was_issued' => true])
                ->makeEloquentViaRequest()
                ->issueReportCardsByEloquent()
        );
    }
}
