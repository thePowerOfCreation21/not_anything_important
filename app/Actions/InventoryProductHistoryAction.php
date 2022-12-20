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
                    'inventory_product_id' => ['required', 'integer'],
                    'action' => ['required', 'in:' . $allowedActionsString],
                    'amount' => ['required', 'integer', 'between:1,100000000'],
                    'date' => ['required', 'date_format:Y-m-d']
                ]
            ])
            ->setCasts([
                'date' => ['jalali_to_gregorian:Y-m-d'],
                'from_date' => ['jalali_to_gregorian:Y-m-d'],
                'to_date' => ['jalali_to_gregorian:Y-m-d'],
            ]);
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
     * @return int
     */
    protected function castAmountByAction (int $amount, string $action): int
    {
        return $action == 'decrease' ? -$amount : $amount;
    }

    /**
     * @param array $data
     * @return object
     * @throws CustomException
     * @throws \Throwable
     */
    protected function updateInventoryProductStock (array $data): object
    {
        $inventoryProduct = (new InventoryProductAction())->getById($data['inventory_product_id']);
        $inventoryProduct->stock = $inventoryProduct->stock + $this->castAmountByAction($data['amount'], $data['action']);

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
}
