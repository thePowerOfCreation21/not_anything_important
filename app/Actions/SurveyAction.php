<?php

namespace App\Actions;

use App\Models\SurveyOptionModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyModel;
use App\Http\Resources\SurveyResource;
use Throwable;

class SurveyAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyModel::class)
            ->setResource(SurveyResource::class)
            ->setValidationRules([
                'store' => [
                    'text' => ['required', 'string', 'max:5000'],
                    'survey_category_id' => ['required', 'integer', 'exists:survey_categories,id'],
                    'options' => ['required', 'array', 'max:4'],
                    'options.*' => ['required', 'string', 'max:250'],
                ],
                'update' => [
                    'text' => ['string', 'max:5000'],
                    'options' => ['array', 'max:4'],
                    'options.*' => ['required', 'array', 'max:4'],
                    'options.*.option_id' => ['required', 'integer'],
                    'options.*.title' => ['required', 'string', 'max:250'],
                ],
                'getQuery' => [
                    'survey_category_id' => ['integer']
                ]
            ])
            ->setQueryToEloquentClosures([
                'survey_category_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('survey_category_id', $query['survey_category_id']);
                }
            ]);
        parent::__construct();
    }

    /**
     * @param array $surveyOptions
     * @param string $surveyId
     * @param bool $deleteOldOptions
     * @return mixed
     * @throws Throwable
     */
    public function storeSurveyOptionsFromRequest (array $surveyOptions, string $surveyId, bool $deleteOldOptions = false): mixed
    {
        if ($deleteOldOptions) SurveyOptionModel::query()->where('survey_id', $surveyId)->delete();

        $survey = SurveyModel::query()
            ->with('surveyCategory')
            ->where('id', $surveyId)
            ->firstOrFail();

        throw_if(empty($survey->surveyCategory), CustomException::class, 'survey_id is wrong (survey does not have any category)', '77910', 400);

        foreach ($surveyOptions AS $key => $surveyOption)
        {
            $surveyOptions[$key] = [
                'survey_id' => $surveyId,
                'survey_category_id' => $survey->surveyCategory->id,
                'title' => $surveyOption
            ];
        }

        return SurveyOptionModel::insert($surveyOptions);
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws Throwable
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
     * @throws Throwable
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        foreach ($this->startEloquentIfIsNull()->eloquent->get() AS $survey)
        {
            // if (isset($updateData['options'])) $this->storeSurveyOptionsFromRequest($updateData['options'], $survey->id, true);
            foreach ($updateData['options'] ?? [] AS $optionData)
            {
                SurveyOptionModel::query()
                    ->where('id', $optionData['option_id'])
                    ->update([
                        'title' => $optionData['title']
                    ]);
            }
        }
        return parent::update($updateData, $updating);
    }
}
