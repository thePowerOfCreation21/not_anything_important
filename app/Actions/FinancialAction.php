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
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        $data['educational_year'] = PardisanHelper::getEducationalYearByGregorianDate($data['date']);

        return parent::store($data, $storing);
    }
}
