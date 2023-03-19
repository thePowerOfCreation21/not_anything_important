<?php

namespace App\Http\Resources;

use App\Helpers\PardisanHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'is_entrance_disabled' => !empty($this->last_entrance_date) && PardisanHelper::isDateToday($this->last_entrance_date),
                'is_exit_disabled' => !empty($this->last_exit_date) && PardisanHelper::isDateToday($this->last_exit_date),
            ]
        );
    }
}
