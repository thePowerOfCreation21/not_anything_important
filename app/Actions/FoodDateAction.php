<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\FoodDateModel;
use App\Http\Resources\FoodDateResource;

class FoodDateAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(FoodDateModel::class)
            ->setResource(FoodDateResource::class);
        parent::__construct();
    }
}
