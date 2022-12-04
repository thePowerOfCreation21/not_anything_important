<?php

namespace App\Actions;

use App\Http\Resources\ClassFileResource;
use App\Models\ClassFileModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassFileAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassFileModel::class)
            ->setResource(ClassFileResource::class);

        parent::__construct();
    }
}
