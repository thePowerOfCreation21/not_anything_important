<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyModel;
use App\Http\Resources\SurveyResource;

class SurveyAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyModel::class)
            ->setResource(SurveyResource::class);
        parent::__construct();
    }
}
