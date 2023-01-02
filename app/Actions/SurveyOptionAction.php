<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyOptionModel;
use App\Http\Resources\SurveyOptionResource;

class SurveyOptionAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyOptionModel::class)
            ->setResource(SurveyOptionResource::class);
        parent::__construct();
    }
}
