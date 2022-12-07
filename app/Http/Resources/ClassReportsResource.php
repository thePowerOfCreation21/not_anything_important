<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassReportsResource extends JsonResource
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
            'date' => Helpers::getCustomDateCast($this->date),
            'period' => $this->period,
            'report' => $this->report,
            'class_course_id' => $this->class_course_id,
            'class_course' => new ClassCourseResource($this->whenLoaded('classCourse'))
        ];
    }
}
