<?php

namespace App\Actions;

use Genocide\Radiocrud\Helpers;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\FinancialModel;
use App\Http\Resources\FinancialResource;
use App\Helpers\PardisanHelper;

class FinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(FinancialModel::class)
            ->setResource(FinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string', 'max:500'],
                    'financial_type_id' => ['required','string', 'max:20'],
                    'amount' => ['required', 'integer', 'max:10000000000'],
                    'educational_year' => ['string', 'max:25'],
                    'date' => ['required', 'date_format:Y-m-d']
                ],
                'update' => [
                    'title' => [ 'string', 'max:255'],
                    'description' => ['string', 'max:500'],
                    'amount' => ['int', 'min:0','max:10000000000'],
                    'educational_year' => ['string', 'max:25'],
                    'date' => ['date_format:Y-m-d']
                ],
                'getQuery' => [
                    'educational_year' => ['string', 'max:50'],
                    'financial_type_id' => ['string', 'max:20'],
                    'search' => ['string', 'max:255'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'financial_type_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('financial_type_id', $query['financial_type_id']);
                },
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
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                }
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['amount'] = PardisanHelper::makeItNumber($data['amount'], ['decimal' => false, 'negative' => false]);
        $data['educational_year'] = $data['educational_year'] ?? PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }

    public function update(array $updateData, callable $updating = null): bool|int
    {
        return parent::update($updateData, $updating);
    }
}
