<?php

namespace App\Http\Controllers;

use App\Actions\StudentDisciplineAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\StudentDisciplineModel;
use App\Actions\StudentFinancialAction;

class StudentDisciplineController extends Controller
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
           'data' => (new StudentDisciplineAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeByRequest()
        ]);
    }
}
