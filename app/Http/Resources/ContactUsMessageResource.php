<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsMessageResource extends JsonResource
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
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'text' => $this->text,
            'is_seen' => $this->is_seen,
            'created_at' => Helpers::getCustomDateCast($this->created_at),
        ];
    }
}
