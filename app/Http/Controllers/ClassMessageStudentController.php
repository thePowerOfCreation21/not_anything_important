<?php

namespace App\Http\Controllers;

use App\Actions\ClassMessageStudentAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassMessageStudentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassMessageStudentAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations(['classMessage'])
                ->makeEloquent()
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
            (new ClassMessageStudentAction())
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations(['classMessage'])
                ->makeEloquent()
                ->getById($id)
                ->seen()
        );
    }
}
