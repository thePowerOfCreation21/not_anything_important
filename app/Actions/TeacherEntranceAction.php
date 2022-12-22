<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherEntranceModel;
use App\Http\Resources\TeacherEntranceResource;

class TeacherEntranceAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(TeacherEntranceModel::class)->setResource(TeacherEntranceResource::class);
        parent::__construct();
    }
}
