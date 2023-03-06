<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\StudentReportCardScoreModel;
use App\Http\Resources\StudentReportCardScoreResource;

class StudentReportCradScoreAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(StudentReportCardScoreModel::class)->setResource(StudentReportCardScoreResource::class);
        parent::__construct();
    }
}
