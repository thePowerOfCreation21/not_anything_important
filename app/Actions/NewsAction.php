<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\NewsModel;
use App\Http\Resources\NewsResource;

class NewsAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(NewsModel::class)
            ->setResource(NewsResource::class);
        parent::__construct();
    }
}
