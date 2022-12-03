<?php

namespace App\Actions;

use App\Http\Resources\ClassMessageStudentResource;
use App\Models\ClassMessageStudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassMessageStudentAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassMessageStudentModel::class)
            ->setResource(ClassMessageStudentResource::class);

        parent::__construct();
    }
}
