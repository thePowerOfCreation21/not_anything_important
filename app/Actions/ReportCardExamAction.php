<?php

namespace App\Actions;

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
}
