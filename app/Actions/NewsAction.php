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
            ->setResource(NewsResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:250'],
                    'content' => ['nullable', 'string', 'max:50000'],
                    'image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg', 'max:5000'],
                    'is_important' => ['boolean']
                ]
            ])
            ->setCasts([
                'image' => ['file', 'nullable']
            ]);
        parent::__construct();
    }
}
