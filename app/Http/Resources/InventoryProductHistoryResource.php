<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryProductHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'action' => $this->action,
            'amount' => $this->amount,
            'date' => Helpers::getCustomDateCast($this->date),
            'inventory_product_id' => $this->inventory_product_id,
            'inventory_product' => new InventoryProductResource($this->whenLoaded('inventoryProduct'))
        ];
    }
}
