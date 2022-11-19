<?php

namespace App\Actions;

use App\Http\Resources\StudentFinancialResource;
use App\Models\StudentFinancialModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class StudentFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentFinancialModel::class)
            ->setResource(StudentFinancialResource::class);

        parent::__construct();
    }
}
