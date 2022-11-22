<?php

namespace App\Actions;

use App\Http\Resources\GeneralStatisticResource;
use App\Models\GeneralStatisticModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class GeneralStatisticAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(GeneralStatisticModel::class)
            ->setResource(GeneralStatisticResource::class);

        parent::__construct();
    }
}
