<?php

namespace App\Actions;

use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\InventoryProductModel;
use App\Http\Resources\InventoryProductResource;
use Illuminate\Database\QueryException;

class InventoryProductAction extends ActionService
{
    public function __construct()
    {
        $this
            ->setModel(InventoryProductModel::class)
            ->setResource(InventoryProductResource::class)
            ->setValidationRules([
                'store' => [
                    'title' => ['required', 'string', 'max:250'],
                    'code' => ['required', 'string', 'max:50'],
                    'stock' => ['integer', 'between:0,1000000'],
                    'description' => ['nullable', 'string', 'max:2500']
                ],
                'update' => [
                    'title' => ['string', 'max:250'],
                    'code' => ['string', 'max:50'],
                    'stock' => ['integer', 'between:0,1000000'],
                    'description' => ['nullable', 'string', 'max:2500']
                ]
            ]);
        parent::__construct();
    }

    /**
     * @param callable $try
     * @return mixed
     * @throws \Throwable
     */
    protected function tryCatch (callable $try): mixed
    {
        try
        {
            return $try();
        }
        catch (QueryException $e)
        {
            throw_if($e->getCode() == '23000', CustomException::class, 'code field is unique', 1136, 400);
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws \Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        return $this->tryCatch(function () use ($data, $storing){
            return parent::store($data, $storing);
        });
    }

    /**
     * @param array $updateData
     * @param callable|null $updating
     * @return bool|int
     */
    public function update(array $updateData, callable $updating = null): bool|int
    {
        return $this->tryCatch(function () use ($updateData, $updating){
            return parent::update($updateData, $updating);
        });
    }
}
