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
            ->setResource(GalleryImageResource::class)
            ->setValidationRules([
                'store' => [
                    'image' => ['required', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000']
                ]
            ])
            ->setCasts([
                'image' => ['file']
            ]);
        parent::__construct();
    }
}
