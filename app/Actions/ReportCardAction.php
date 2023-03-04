<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ReportCardModel;
use App\Http\Resources\ReportCardResource;

class ReportCardAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(ReportCardModel::class)->setResource(ReportCardResource::class);
        parent::__construct();
    }
}
