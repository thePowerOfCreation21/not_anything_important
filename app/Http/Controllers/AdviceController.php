<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdviceModel;
use App\Actions\AdviceAction;
use Illuminate\Http\JsonResponse;
use Genocide\Radiocrud\Exceptions\CustomException;

class AdviceController extends Controller
{
    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateById(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new AdviceAction())
                ->setRequest($request)
                ->setValidationRule('update')
                ->updateByIdAndRequest($id)
        ]);
    }
}
