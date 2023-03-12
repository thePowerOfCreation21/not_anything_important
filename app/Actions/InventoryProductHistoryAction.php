<?php

namespace App\Actions;

use App\Models\InventoryProductModel;
use Genocide\Radiocrud\Exceptions\CustomException;
use Genocide\Radiocrud\Services\ActionService\ActionService;
use App\Models\InventoryProductHistoryModel;
use App\Http\Resources\InventoryProductHistoryResource;

class InventoryProductHistoryAction extends ActionService
{
    public function __construct()
    {
        $allowedActionsString = implode(',', $this->getAllowedActions());

        $this
            ->setModel(InventoryProductHistoryModel::class)
            ->setResource(InventoryProductHistoryResource::class)
            ->setValidationRules([
                'store' => [
                    'description' => ['string', 'max:5000'],
                    'inventory_product_id' => ['required', 'integer'],
                    'action' => ['required', 'in:' . $allowedActionsString],
                    'amount' => ['required', 'integer', 'between:1,100000000'],
                    'date' => ['required', 'date_format:Y-m-d H:i']
                ],
                'getQuery' => [
                    'inventory_product_id' => ['integer'],
                    'inventory_product_code' => ['string', 'max:50'],
                    'from_date' => ['date_format:Y-m-d'],
                    'to_date' => ['date_format:Y-m-d'],
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d H:i'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ])
            ->setQueryToEloquentClosures([
                'inventory_product_id' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->where('inventory_product_id', $query['inventory_product_id']);
                },
                'inventory_product_code' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereHas('inventoryProduct', function($q) use ($query){
                        $q->where('code', $query['inventory_product_code']);
                    });
                },
                'from_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '>=', $query['from_date']);
                },
                'to_date' => function (&$eloquent, $query)
                {
                    $eloquent = $eloquent->whereDate('date', '<=', $query['to_date']);
                },
            ])
            ->setOrderBy(['date' => 'DESC', 'id' => 'DESC']);
        parent::__construct();
    }

    /**
     * @return string[]
     */
    public function getAllowedActions (): array
    {
        return ['increase', 'decrease'];
    }

    /**
     * @param int $amount
     * @param string $action
     * @param bool $reverse
     * @return int
     */
    protected function castAmountByAction (int $amount, string $action, bool $reverse = false): int
    {
        $amount =  $action == 'decrease' ? -$amount : $amount;
        return $reverse ? -$amount : $amount;
    }

    /**
     * @param array $data
     * @param bool $reverse
     * @return object
     * @throws CustomException
     * @throws \Throwable
     */
    protected function updateInventoryProductStock (array $data, bool $reverse = false): object
    {
        $inventoryProduct = (new InventoryProductAction())
            ->applyResource(false)
            ->getById($data['inventory_product_id']);

        $inventoryProduct->stock += $this->castAmountByAction($data['amount'], $data['action'], $reverse);

        throw_if($inventoryProduct->stock < 0, CustomException::class, 'inventory product stock should not become less than 0', '1152', 400);

        $inventoryProduct->save();

        return $inventoryProduct;
    }

    /**
     * @param array $data
     * @param callable|null $storing
     * @return mixed
     * @throws CustomException
     * @throws \Throwable
     */
    public function store(array $data, callable $storing = null): mixed
    {
        $this->updateInventoryProductStock($data);

        return parent::store($data, $storing);
    }

    /**
     * @param callable|null $deleting
     * @return mixed
     * @throws CustomException
     * @throws \Throwable
     */
    public function delete(callable $deleting = null): mixed
    {
        foreach ($this->getEloquent()->get() AS $inventoryProductHistory)
        {
            $this->updateInventoryProductStock($inventoryProductHistory->toArray(), true);
        }

        return parent::delete($deleting);
    }
}
