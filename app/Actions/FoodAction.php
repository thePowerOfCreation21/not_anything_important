<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\FoodModel;
use App\Http\Resources\FoodResource;

class FoodAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(FoodModel::class)
            ->setResource(FoodResource::class);
        parent::__construct();
    }
}
