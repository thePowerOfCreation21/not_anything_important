<?php

namespace App\Http\Controllers;

use App\Actions\ReportCardExamAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportCardExamController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new ReportCardExamAction())
                ->setRequest($request)
                ->setValidationRule('get')
                ->setRelations(['reportCard.classModel', 'course'])
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
            (new ReportCardExamAction())
                ->setRelations(['reportCard.classModel', 'course', 'reportCardExamScores.studentModel'])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
