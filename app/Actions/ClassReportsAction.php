<?php

namespace App\Actions;


use App\Http\Resources\ClassReportsResource;
use App\Models\ClassReportsModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassReportsAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassReportsModel::class)
            ->setResource(ClassReportsResource::class)
            ->setValidationRules([
                'store' => [
                    'telegram' => ['required', 'string', 'min:2', 'max:5000']
                ],
                'update' => [
                    'telegram' => ['string', 'min:2', 'max:5000']
                ]
            ]);
        parent::__construct();
    }
}
