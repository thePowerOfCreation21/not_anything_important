<?php

namespace App\Actions;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\WalletHistoryModel;
use App\Http\Resources\WalletHistoryResource;

class WalletHistoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(WalletHistoryModel::class)
            ->setResource(WalletHistoryResource::class);

        parent::__construct();
    }
}
