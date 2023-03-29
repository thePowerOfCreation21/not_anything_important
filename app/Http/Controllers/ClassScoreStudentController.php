<?php

namespace App\Http\Controllers;

use App\Actions\ClassScoreStudentAction;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassScoreStudentController extends Controller
{
    public function get (Request $request)
    {
        return response()->json(
            (new ClassScoreStudentAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations(['classScore.classCourse.classModel'])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreStudentAction())
                ->setRequest($request)
                ->setValidationRule('getByStudent')
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations([
                    'classScore' => [
                        'classCourse' => ['course', 'teacher', 'classModel'],
                        'submitter',
                    ],
                    'student'
                ])
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
            (new ClassScoreStudentAction())
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setRelations([
                    'classScore' => [
                        'classCourse' => ['course', 'teacher', 'classModel'],
                        'submitter',
                    ],
                    'student'
                ])
                ->makeEloquent()
                ->getById($id)
        );
    }
}
