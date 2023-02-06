<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassMessageStudentResource extends JsonResource
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
            'is_seen' => (bool) $this->is_seen,
            'class_message_id' => $this->class_message_id,
            'class_message' => new ClassMessagesResource($this->whenLoaded('classMessage')),
            'student_id' => $this->student_id,
            'student' => new StudentCollectionResource($this->whenLoaded('student'))
        ];
    }
}
