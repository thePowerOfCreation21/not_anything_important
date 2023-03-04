<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardExamScoreModel;
use App\Http\Resources\ReportCardExamScoreResource;

class ReportCardExamScoreAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(ReportCardExamScoreModel::class)->setResource(ReportCardExamScoreResource::class);
        parent::__construct();
    }
}
