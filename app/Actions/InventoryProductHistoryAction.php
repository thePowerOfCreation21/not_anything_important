<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\InventoryProductHistoryModel;
use App\Http\Resources\InventoryProductHistoryResource;

class InventoryProductHistoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(InventoryProductHistoryModel::class)
            ->setResource(InventoryProductHistoryResource::class);
        parent::__construct();
    }
}
