<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\Financial;
use App\Http\Resources\FinancialResource;
use App\Helpers\PardisanHelper;

class FinancialAction extends ActionService
{
    public function __construct()
    {

        parent::__construct();
    }
}
