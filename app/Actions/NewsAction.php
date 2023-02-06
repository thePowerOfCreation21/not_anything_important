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
                ],
                'get' => [
                    'search' => ['string' , 'max:250'],
                    'is_important' => ['boolean']
                ]
            ])
            ->setCasts([
                'image' => ['file', 'nullable']
            ])
            ->setQueryToEloquentClosures([
                'search' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('title', 'LIKE', "%{$query['search']}%");
                },
                'is_important' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('is_important', $query['is_important']);
                }
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
     * @return void
     */
    protected function deleteImagesByEloquent ()
    {
        foreach ($this->startEloquentIfIsNull()->eloquent->get() AS $news)
            if (is_file($news->image)) unlink($news->image);
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        cache()->forget('homePage');

        // deleting old image file, if "image" field is present at $updateData
        if (array_key_exists('image', $updateData)) $this->deleteImagesByEloquent();

        return parent::update($updateData, $updating);
    }

    /**
     * @param callable|null $deleting
     * @return mixed
     */
    public function delete(callable $deleting = null): mixed
    {
        cache()->forget('homePage');

        $this->deleteImagesByEloquent();

        return parent::delete($deleting);
    }
}
