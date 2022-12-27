<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\TeacherEntranceHistoryModel;
use App\Http\Resources\TeacherEntranceHistoryResource;

class TeacherEntranceHistoryAction extends ActionService
{
    public function __construct()
    {
        $this->setModel(TeacherEntranceHistoryModel::class)->setResource(TeacherEntranceHistoryResource::class);
        parent::__construct();
    }
}
