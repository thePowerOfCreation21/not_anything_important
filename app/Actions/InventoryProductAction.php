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
            ]);
        parent::__construct();
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws \Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        try
        {
            return parent::store($data, $storing);
        }
        catch (QueryException $e)
        {
            throw_if($e->getCode() == '23000', CustomException::class, 'code field is unique', 1136, 400);
            throw $e;
        }
    }
}
