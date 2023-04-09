<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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
            'text' => $this->text,
            'participants_count' => (int)$this->participants_count,
            'teacher_id' => $this->teacher_id,
            'teacher' => $this->whenLoaded('teacher'),
            'options' => SurveyOptionResource::collection($this->whenLoaded('surveyOptions'))
        ];
    }
}
