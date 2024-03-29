<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'financial_type_id' => $this->financial_type_id,
            'financial_type' => new FinancialTypeResource($this->whenLoaded('financialType')),
            'amount' => $this->amount,
            'educational_year' => $this->educational_year,
            'date' => Helpers::getCustomDateCast($this->date)
        ];
    }
}
