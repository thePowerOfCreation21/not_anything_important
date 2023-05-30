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
                'store' => [
                    'text' => ['required', 'string', 'max:20000'],
                    'is_active' => ['boolean'],
                    'type' => ['required', 'in:teacher,student']
                ],
                'update' => [
                    'text' => ['string', 'max:20000'],
                    'is_active' => ['boolean']
                ],
                'get' => [
                    'student_id' => ['integer'],
                    'type' => ['in:student,teacher'],
                    'is_active' => ['boolean'],
                ],
                'getByStudent' => [
                    'is_active' => ['boolean']
                ],
                'getByTeacher' => [
                    'is_active' => ['boolean']
                ],
            ])
            ->setQueryToEloquentClosures([
                'type' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('type', $query['type']);
                },
                'is_active' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('is_active', $query['is_active']);
                },
                'has_answered' => function (&$eloquent, $query)
                {
                    $user_id = $query['student_id'] ?? $query['teacher_id'] ?? null;
                    if ($query['has_answered'])
                        $eloquent = $eloquent->whereHas('surveyAnswers', function ($q) use($query, $user_id){
                            $q->where('participant_id', $user_id);
                        });
                    else
                        $eloquent = $eloquent->whereDoesntHave('surveyAnswers', function ($q) use($query, $user_id){
                            $q->where('participant_id', $user_id);
                        });
                }
            ]);
        parent::__construct();
    }
}
