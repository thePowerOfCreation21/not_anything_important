<?php

namespace App\Actions;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Helpers\PardisanHelper;
use App\Models\StudentDisciplineModel;

class StudentDisciplineAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentDisciplineModel::class);

        parent::__construct();
    }
}
