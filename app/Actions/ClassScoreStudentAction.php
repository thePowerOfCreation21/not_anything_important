<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\ClassScoreStudentModel;
use App\Http\Resources\ClassScoreStudentResource;

class ClassScoreStudentAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(ClassScoreStudentModel::class)->setResource(ClassScoreStudentResource::class);
        parent::__construct();
    }
}
