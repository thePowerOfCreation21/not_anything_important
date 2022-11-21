<?php

namespace App\Actions;

use App\Http\Resources\StudentFinancialResource;
use App\Models\StudentFinancialModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Actions\StudentAction;

class StudentFinancialAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentFinancialModel::class)
            ->setResource(StudentFinancialResource::class)
            ->setValidationRules([
                'store' => [
                    'student_id' => ['required', 'string', 'max:20'],
                    'amount' => ['required', 'int', 'min:0', 'max:100000000'],
                    'date' => ['required', 'date_format:Y-m-d'],
                    'paid' => ['bool'],
                ],
                'update' => [
                    'amount' => ['int', 'min:0', 'max:100000000'],
                    'date' => ['date_format:Y-m-d'],
                    'paid' => ['bool']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d']
            ]);

        parent::__construct();
    }

    public function store(array $data, callable $storing = null): mixed
    {
        dd($data);
        return parent::store($data, $storing);
    }
}
