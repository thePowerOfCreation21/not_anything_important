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
                ->setRelations(['reportCard.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }
}
