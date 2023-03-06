<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\StudentReportCardModel;
use App\Http\Resources\StudentReportCardResource;

class StudentReportCradAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(StudentReportCardModel::class)->setResource(StudentReportCardResource::class);
        parent::__construct();
    }
}
