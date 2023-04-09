<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyCategoryModel;
use App\Http\Resources\SurveyCategoryResource;

class SurveyCategoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyCategoryModel::class)
            ->setResource(SurveyCategoryResource::class);
        parent::__construct();
    }
}
