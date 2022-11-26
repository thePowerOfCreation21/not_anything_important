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
}
