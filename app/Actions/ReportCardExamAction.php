<?php

namespace App\Actions;

use App\Models\ReportCardExamScoreModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardExamModel;
use App\Http\Resources\ReportCardExamResource;

class ReportCardExamAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ReportCardExamModel::class)
            ->setResource(ReportCardExamResource::class)
            ->setValidationRules([
                'get' => [
                    'report_card_id' => ['integer'],
                    'course_id' => ['integer'],
                    'class_id' => ['integer'],
                ],
                'update' => [
                    'reportCardExamScores' => ['array', 'max:100'],
                    'reportCardExamScores.*' => ['array'],
                    'reportCardExamScores.*.student_id' => ['required', 'integer'],
                    'reportCardExamScores.*.score' => ['float', 'between:0.00,100.00'],
                    'reportCardExamScores.*.is_present' => ['boolean']
                ]
            ])
            ->setQueryToEloquentClosures([
                'report_card_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('report_card_id', $query['report_card_id']);
                },
                'course_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('course_id', $query['course_id']);
                },
                'class_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('reportCard', function($q) use($query){
                        $q->where('report_cards.class_id', $query['class_id']);
                    });
                }
            ]);
        parent::__construct();
    }

    /**
     * @param string $id
     * @param callable|null $updating
     * @return bool|int
     * @throws CustomException
     */
    public function updateByIdAndRequest(string $id, callable $updating = null): bool|int
    {
        $updateData = $this->getDataFromRequest();
        $updateData['reportCardExamId'] = $id;

        return parent::update($updateData, $updating);
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        foreach ($updateData['reportCardExamScores'] ?? [] AS $reportCardExamScoreUpdateData)
        {
            if (isset($reportCardExamScoreUpdateData['score'])) $reportCardExamScoreUpdateData['is_present'] = true;

            ReportCardExamScoreModel::query()
                ->where('report_card_exam_id', $updateData['reportCardExamId'])
                ->where('student_id', $reportCardExamScoreUpdateData['student_id'])
                ->update($reportCardExamScoreUpdateData);
        }

        return true;
    }
}
