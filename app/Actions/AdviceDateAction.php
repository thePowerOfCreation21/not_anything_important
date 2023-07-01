<?php

namespace App\Actions;

use App\Helpers\PardisanHelper;
use App\Http\Resources\AdviceDateResource;
use App\Models\AdviceDateModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class AdviceDateAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AdviceDateModel::class)
            ->setResource(AdviceDateResource::class)
            ->setValidationRules([
                'store' => [
                    'date' => ['required', 'string']
                ],
                'update' => [
                    'date' => ['string']
                ],
                'getQuery' => [
                    'educational_year' => ['string', 'max:50'],
                    'from_date' => ['string'],
                    'to_date' => ['string'],
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'educational_year' => function (&$eloquent, $query) {
                    if ($query['educational_year']  != '*') {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                },
                'from_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query) {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
            ]);
        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        if (isset($updateData['date'])) {
            $updateData['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($updateData['date']);
        }

        return parent::update($updateData, $updating);
    }
}
