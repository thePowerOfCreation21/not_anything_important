<?php

namespace App\Actions;


use App\Models\ClassReportsModel;
use App\Http\Resources\ClassResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassReportsAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassReportsModel::class)
            ->setResource(ClassResource::class)
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
