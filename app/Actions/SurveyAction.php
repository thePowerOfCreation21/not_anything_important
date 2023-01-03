<?php

namespace App\Actions;

use App\Models\SurveyOptionModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyModel;
use App\Http\Resources\SurveyResource;

class SurveyAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyModel::class)
            ->setResource(SurveyResource::class)
            ->setValidationRules([
                'store' => [
                    'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
                    'text' => ['required', 'string', 'max:5000'],
                    'is_active' => ['boolean'],
                    'options' => ['required', 'array', 'max:4'],
                    'options.*' => ['required', 'string', 'max:250'],
                ],
                'getQuery' => [
                    'student_id' => ['integer'],
                    'teacher_id' => ['integer'],
                    'is_active' => ['boolean']
                ],
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

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $survey = parent::store($data, $storing);

        foreach ($data['options'] AS $key => $surveyOption)
        {
            $data['options'][$key] = [
                'survey_id' => $survey->id,
                'title' => $surveyOption
            ];
        }

        SurveyOptionModel::insert($data['options']);

        return $survey;
    }
}
