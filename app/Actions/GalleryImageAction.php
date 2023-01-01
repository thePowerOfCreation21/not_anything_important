<?php

namespace App\Actions;

use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\GalleryImageModel;
use App\Http\Resources\GalleryImageResource;

class GalleryImageAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(GalleryImageModel::class)
            ->setResource(GalleryImageResource::class);
        parent::__construct();
    }
}
