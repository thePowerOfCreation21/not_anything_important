<?php

namespace App\Actions;

use App\Models\AdviceStudentPivotModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Http\Resources\AdviceStudentPivotResource;

class AdviceStudentPivotAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(AdviceStudentPivotModel::class)->setResource(AdviceStudentPivotResource::class);
        parent::__construct();
    }
}
