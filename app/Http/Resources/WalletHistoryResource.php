<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletHistoryResource extends JsonResource
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
            'action' => $this->action,
            'created_at' => Helpers::getCustomDateCast($this->created_at),
            'updated_at' => $this->updated_at,
            'student_id' => $this->student_id,
            'student' => $this->whenLoaded('student'),
            'charged_by_id' => $this->charged_by_id
        ];
    }
}
