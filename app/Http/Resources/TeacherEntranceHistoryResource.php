<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherEntranceHistoryResource extends JsonResource
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
            'week_day' => $this->week_day,
            'entrance' => substr($this->entrance, 0, 5),
            'exit' => empty($this->exit) ? '00:00' : substr($this->exit, 0, 5),
            'late_string' => $this->late_string,
            'date' => Helpers::getCustomDateCast($this->date),
            'teacher_id' => $this->teacher_id,
            'teacher' => new TeacherResource($this->whenLoaded('teacher'))
        ];
    }
}
