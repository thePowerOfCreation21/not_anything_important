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
        $this
            ->setModel(Financial::class)
            ->setResource(FinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string', 'max:500'],
                    'financial_type_id' => ['required','string', 'max:20'],
                    'amount' => ['required', 'int', 'min:0','max:100000000'],
                    'date' => ['required', 'date_format:Y-m-d']
                ],
                'getQuery' => [
                    'educational_year' => ['string', 'max:50'],
                    'financial_type_id' => ['string', 'max:20'],
                    'search' => ['string', 'max:255']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ])
            ->setQueryToEloquentClosures([
                'financial_type_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('financial_type_id', $query['financial_type_id']);
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
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
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
