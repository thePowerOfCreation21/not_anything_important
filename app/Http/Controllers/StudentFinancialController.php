<?php

namespace App\Http\Controllers;

use App\Actions\StudentFinancialAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentFinancialController extends Controller
{
    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentFinancialAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeStudentFinancialById($id)
        ]);
    }
}
