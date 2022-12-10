<?php

namespace App\Actions;

use App\Http\Resources\FinancialTypeResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\FinancialType;

class FinancialTypeAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(FinancialType::class)
            ->setResource(FinancialTypeResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:255']
                ],
                'update' => [
                    'title' => ['string', 'max:255']
                ]
            ]);

        parent::__construct();
    }
}
