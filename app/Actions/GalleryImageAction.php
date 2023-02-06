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

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     */
    public function store(array $data, callable $storing = null): mixed
    {
        cache()->forget('homePage');

        return parent::store($data, $storing);
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        cache()->forget('homePage');

        return parent::update($updateData, $updating);
    }

    /**
     * @param callable|null $deleting
     * @return mixed
     */
    public function delete(callable $deleting = null): mixed
    {
        cache()->forget('homePage');

        return parent::delete($deleting);
    }
}
