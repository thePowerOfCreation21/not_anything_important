<?php

namespace App\Actions;

use App\Http\Resources\AttendanceResource;
use Genocide\Radiocrud\Services\ActionService\ActionService;

class AttendanceAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(AttendanceAction::class)
            ->setResource(AttendanceResource::class)
            ->setOrderBy(['date' => 'DESC', 'id' => 'DESC']);

        parent::__construct();
    }
}
