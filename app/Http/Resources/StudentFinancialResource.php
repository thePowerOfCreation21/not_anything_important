<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFinancialResource extends JsonResource
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
            'amount' => $this->amount,
            'paid' => $this->paid,
            'date' => Helpers::getCustomDateCast($this->date),
            'educational_year' => $this->educational_year,
            'student' => $this->whenLoaded('student', new StudentResource($this->student))
        ];
    }
}
