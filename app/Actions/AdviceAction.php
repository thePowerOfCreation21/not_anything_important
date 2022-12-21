<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\AdviceModel;
use App\Http\Resources\AdviceResource;

class AdviceAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AdviceModel::class)
            ->setResource(AdviceResource::class)
            ->setValidationRules([
                'update' => [
                    'hour' => ['date_format:H:i:s'],
                    'date' => ['date_format:Y-m-d'],
                    'status' => ['string', 'in:pending,accepted,rejected']
                ]
            ]);
        parent::__construct();
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if(isset($updateData['date']))
        {
            $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
        }

        return parent::update($updateData, $updating);
    }
}
