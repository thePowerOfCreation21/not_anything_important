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
                    'hour' => ['string', 'max:50'],
                    'date' => ['date_format:Y-m-d'],
                    'status' => ['string', 'in:pending,accepted,rejected', 'max:255']
                ],
                'getQuery' => [
                    'search' => ['string', 'max:255'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('status', 'LIKE', "%{$query['search']}%");
                }
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
