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
                    'date' => ['required', 'date_format:Y-m-d']
                ],
                'getQuery' => [
                    'educational_year' => ['string', 'max:50']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ])
            ->setQueryToEloquentClosures([
                'educational_year' => function (&$eloquent, $query)
                {
                    if ($query['educational_year']  != '*')
                    {
                        $eloquent = $eloquent->where('educational_year', $query['educational_year']);
                    }
                }
            ]);
        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }
}
