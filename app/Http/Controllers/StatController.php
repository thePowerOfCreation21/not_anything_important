<?php

namespace App\Http\Controllers;

use App\Actions\StatAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new StatAction())->getByStudentId($request->user()->id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new StatAction())->getByTeacherId($request->user()->id)
        );
    }

    /**
     * @return JsonResponse
     */
    public function getByAdmin (): JsonResponse
    {
        return response()->json(
            (new StatAction())->getByAdmin()
        );
    }
}
