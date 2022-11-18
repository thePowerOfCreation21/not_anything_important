<?php

namespace App\Http\Controllers;

use App\Actions\StudentAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function storeByAdmin (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new StudentAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->setDefaultRegisterStatus('added_by_admin')
                ->storeByRequest()
        ]);
    }
}
