<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardExamModel;
use App\Http\Resources\ReportCardExamResource;

class ReportCardExamAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(ReportCardExamModel::class)->setResource(ReportCardExamResource::class);
        parent::__construct();
    }
}
