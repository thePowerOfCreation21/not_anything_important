<?php

namespace App\Actions;

use App\Models\SurveyAnswerModel;
use App\Models\SurveyOptionModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyCategoryModel;
use App\Http\Resources\SurveyCategoryResource;

class SurveyCategoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyCategoryModel::class)
            ->setResource(SurveyCategoryResource::class)
            ->setValidationRules([
                'answer' => [
                    'survey_category_id' => ['required', 'integer'],
                    'options' => ['required', 'array', 'max:100'],
                    'options.*' => ['integer']
                ],
                'store' => [
                    'text' => ['required', 'string', 'max:20000'],
                    'is_active' => ['boolean'],
                    'teacher_id' => ['integer']
                ],
                'update' => [
                    'text' => ['string', 'max:20000'],
                    'is_active' => ['boolean']
                ],
                'get' => [
                    'student_id' => ['integer'],
                    'teacher_id' => ['integer'],
                    'is_active' => ['boolean']
                ],
                'getByStudent' => [
                    'teacher_id' => ['integer'],
                    'is_active' => ['boolean']
                ]
            ])
            ->setQueryToEloquentClosures([
                'student_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->forStudent($query['student_id']);
                },
                'teacher_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('teacher_id', $query['teacher_id']);
                },
                'is_active' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('is_active', $query['is_active']);
                }
            ]);
        parent::__construct();
    }
}
