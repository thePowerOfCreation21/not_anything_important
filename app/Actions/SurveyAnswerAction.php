<?php

namespace App\Actions;

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
                    'survey_option_id' => ['required', 'integer'],
                ]
            ]);
        parent::__construct();
    }

    public function storeByRequest(callable $storing = null): mixed
    {
        $data = $this->getDataFromRequest();

        $data['student_id'] = $this->request->user()->id;

        $surveyOption = SurveyOptionModel::query()
            ->with('survey')
            ->whereHas('survey', function($q) {
                $q->where('is_active', true);
            })
            ->whereDoesntHave('surveyAnswers', function($q) use($data){
                $q->where('student_id', $data['student_id']);
            })
            ->where('id', $data['survey_option_id'])
            ->firstOrFail();

        SurveyOptionModel::query()
            ->where('id', $data['survey_option_id'])
            ->update([
                'participants_count' => $surveyOption->participants_count + 1
            ]);

        SurveyModel::query()
            ->where('id', $surveyOption->survey->id)
            ->update([
                'participants_count' => $surveyOption->survey->participants_count + 1
            ]);


        return parent::store($data, $storing);
    }
}
