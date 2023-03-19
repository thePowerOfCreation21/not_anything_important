<?php

namespace App\Actions;

use App\Models\SurveyOptionModel;
use Genocide\Radiocrud\Exceptions\CustomException;
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
                'update' => [
                    'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
                    'text' => ['string', 'max:5000'],
                    'is_active' => ['boolean'],
                    'options' => ['array', 'max:4'],
                    'options.*' => ['required', 'string', 'max:250'],
                ],
                'getQuery' => [
                    'student_id' => ['integer'],
                    'teacher_id' => ['integer'],
                    'is_active' => ['boolean']
                ],
                'getByTeacher' => [
                    'student_id' => ['integer'],
                    'is_active' => ['boolean']
                ],
                'getByStudent' => [
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
     * @param array $surveyOptions
     * @param string $surveyId
     * @param bool $deleteOldOptions
     * @return mixed
     */
    public function storeSurveyOptionsFromRequest (array $surveyOptions, string $surveyId, bool $deleteOldOptions = false): mixed
    {
        if ($deleteOldOptions) SurveyOptionModel::query()->where('survey_id', $surveyId)->delete();

        foreach ($surveyOptions AS $key => $surveyOption)
        {
            $surveyOptions[$key] = [
                'survey_id' => $surveyId,
                'title' => $surveyOption
            ];
        }

        return SurveyOptionModel::insert($surveyOptions);
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $survey = parent::store($data, $storing);

        $this->storeSurveyOptionsFromRequest($data['options'], $survey->id);

        return $survey;
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        foreach ($this->startEloquentIfIsNull()->eloquent AS $survey)
        {
            if (isset($data['options'])) $this->storeSurveyOptionsFromRequest($data['options'], $survey->id, true);
        }
        return parent::update($updateData, $updating);
    }
}
