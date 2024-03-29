<?php

namespace App\Http\Controllers;

use App\Actions\ClassScoreAction;
use App\Helpers\PardisanHelper;
use App\Http\Resources\GroupByDateResource;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassScoreController extends Controller
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
            'data' => (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('storeByAdmin')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */

    public function updateById (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function get (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations([
                    'classCourse' => ['course', 'teacher', 'classModel'],
                    'submitter'
                ])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getGroupByDate (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
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
            (new ClassScoreAction())
                ->setRelations([
                    'classCourse' => ['course', 'teacher', 'classModel'],
                    'classScoreStudents' => ['student'],
                    'submitter'
                ])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function deleteById (string $id): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())->deleteById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function storeByTeacher (Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('storeByTeacher')
                ->storeByRequest()
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updateByIdByTeacher (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('updateByAdmin')
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquent()
                ->updateByIdAndRequest($id)
        ]);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteByIdByTeacher (string $id, Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new ClassScoreAction())
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquent()
                ->deleteById($id)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setRelations([
                    'classCourse' => ['course', 'teacher', 'classModel'],
                    'submitter'
                ])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquentViaRequest()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getGroupByDateByTeacher (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getByIdByTeacher (string $id, Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRelations([
                    'classCourse' => ['course', 'teacher', 'classModel'],
                    'classScoreStudents' => ['student'],
                    'submitter'
                ])
                ->mergeQueryWith(['teacher_id' => $request->user()->id])
                ->makeEloquent()
                ->getById($id)
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function getGroupByDateByStudent (Request $request): JsonResponse
    {
        return response()->json(
            (new ClassScoreAction())
                ->setRequest($request)
                ->setValidationRule('getQuery')
                ->setResource(GroupByDateResource::class)
                ->mergeQueryWith(['educational_year' => PardisanHelper::getCurrentEducationalYear()])
                ->mergeQueryWith(['student_id' => $request->user()->id])
                ->setOrderBy(['date' => 'DESC'])
                ->makeEloquentViaRequest()
                ->groupByDate()
                ->getByRequestAndEloquent()
        );
    }
}
