<?php

namespace App\Actions;

use App\Models\StudentModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class StudentAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(StudentModel::class);

        parent::__construct();
    }
}
