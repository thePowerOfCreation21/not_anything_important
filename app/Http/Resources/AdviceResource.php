<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class AdviceResource extends JsonResource
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
            'status' => $this->status,
            'hour' => new AdviceHourResource($this->whenLoaded('adviceHour')),
            'date' => new AdviceDateResource($this->whenLoaded('adviceDate')),
            'student' => new StudentCollectionResource($this->whenLoaded('student')),
        ];
    }
}
