<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\InventoryProductModel;
use App\Http\Resources\InventoryProductResource;

class InventoryProductAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(InventoryProductModel::class)->setResource(InventoryProductResource::class);
        parent::__construct();
    }
}
