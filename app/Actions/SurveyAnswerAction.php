<?php

namespace App\Actions;

use App\Models\SurveyCategoryModel;
use App\Models\SurveyModel;
use App\Models\SurveyOptionModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyAnswerModel;
use App\Http\Resources\SurveyAnswerResource;

class SurveyAnswerAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyAnswerModel::class)
            ->setResource(SurveyAnswerResource::class)
            ->setValidationRules([
                'store' => [
                    'survey_category_id' => ['required', 'integer'],
                    'options' => ['required', 'array', 'max:100'],
                    'options.*' => ['integer']
                ],
            ]);
        parent::__construct();
    }

    /**
     * @throws \Throwable
     * @throws CustomException
     */
    public function storeAnswerByRequest (): bool
    {
        $data = $this
            ->setValidationRule('store')
            ->getDataFromRequest();

        $participant = $this->request->user();
        $participantClass = get_class($participant);

        throw_if(
            ! SurveyCategoryModel::query()
                ->where('id', $data['survey_category_id'])
                ->where('is_active', true)
                ->exists(),
            CustomException::class, 'survey_category_id is wrong (maybe survey category is not active)'
        );

        throw_if(
            SurveyAnswerModel::query()
                ->where('participant_id', $participant->id)
                ->where('participant_type', $participantClass)
                ->where('survey_category_id', $data['survey_category_id'])
                ->exists(),
            CustomException::class, 'participant already answered this survey category', '92477', 400
        );

        $hashMapSurveyId = [];
        $surveyAnswers = [];

        $surveyOptions = SurveyOptionModel::query()
            ->where('survey_category_id', $data['survey_category_id'])
            ->whereIn('id', $data['options'])
            ->get();

        foreach ($surveyOptions AS $surveyOption)
        {
            if (! isset($hashMapSurveyId[$surveyOption->survey_id]))
            {
                $hashMapSurveyId[$surveyOption->survey_id] = 1;

                $surveyAnswers[] = [
                    'participant_id' => $participant->id,
                    'participant_type' => $participantClass,
                    'survey_category_id' => $data['survey_category_id'],
                    'survey_id' => $surveyOption->survey_id,
                    'survey_option_id' => $surveyOption->id
                ];
            }
        }

        return SurveyAnswerModel::query()->insert($surveyAnswers);
    }
}
