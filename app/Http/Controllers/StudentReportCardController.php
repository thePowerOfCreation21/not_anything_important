<?php

namespace App\Http\Controllers;

use App\Actions\StudentReportCardAction;
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
}
