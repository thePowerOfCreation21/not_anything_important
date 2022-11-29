<?php

namespace App\Actions;

use App\Models\ClassModel;
use App\Http\Resources\ClassResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassModel::class)
            ->setResource(ClassResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'min:2', 'max:250'],
                    'level' => ['string', 'min:2', 'max:20'],
                    'educational_year' => ['string', 'max:50']
                ],
            ]);
        parent::__construct();
    }
}
