<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassScoreStudentResource extends JsonResource
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
            'id' => $this->get,
            'score' => $this->score,
            'late' => $this->late,
            'student_id' => $this->student_id,
            'student' => new StudentCollectionResource($this->whenLoaded('student')),
            'class_score_id' => $this->class_score_id,
            'class_score' => new ClassScoreResource($this->whenLoaded('class_score'))
        ];
    }
}
