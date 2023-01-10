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
                ],
                'update' => [
                    'title' => ['string', 'max:250'],
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

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        // deleting old image file, if "image" field is present at $updateData
        foreach ($this->startEloquentIfIsNull()->eloquent->get() AS $product)
            if (array_key_exists('image', $updateData) && is_file($product->image)) unlink($product->image);

        return parent::update($updateData, $updating);
    }
}
