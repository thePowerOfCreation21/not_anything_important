<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyOptionResource extends JsonResource
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
            'participants_count' => (int)$this->participants_count,
            'survey' => $this->whenLoaded('survey'),
        ];
    }
}
