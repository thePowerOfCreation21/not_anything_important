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
                ]
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
