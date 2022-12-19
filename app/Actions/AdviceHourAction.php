<?php

namespace App\Actions;

use App\Http\Resources\AdviceHourResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\AdviceHourModel;

class AdviceHourAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AdviceHourModel::class)
            ->setResource(AdviceHourResource::class)
            ->setValidationRules([
                'store' => [
                    'hour' => ['required', 'date_format:H:i:s']
                ],
                'update' => [
                    'hour' => ['date_format:H:i:s']
                ]
            ]);
        parent::__construct();
    }
}
