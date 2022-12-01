<?php

namespace App\Actions;

use App\Http\Resources\ClassCourseResource;
use App\Models\ClassCourseModel;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class ClassCourseAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(ClassCourseModel::class)
            ->setResource(ClassCourseResource::class);

        parent::__construct();
    }
}
