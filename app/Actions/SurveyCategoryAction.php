<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\SurveyCategoryModel;
use App\Http\Resources\SurveyCategoryResource;

class SurveyCategoryAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(SurveyCategoryModel::class)
            ->setResource(SurveyCategoryResource::class)
            ->setValidationRules([
                'store' => [
                    'text' => ['required', 'string', 'max:20000'],
                    'is_active' => ['boolean']
                ],
                'update' => [
                    'text' => ['string', 'max:20000'],
                    'is_active' => ['boolean']
                ]
            ]);
        parent::__construct();
    }
}
