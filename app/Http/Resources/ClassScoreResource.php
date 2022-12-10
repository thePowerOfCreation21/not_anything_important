<?php

namespace App\Http\Resources;

use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassScoreResource extends JsonResource
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
            'educational_year' => $this->educational_year,
            'max_score' => $this->max_score,
            'class_course_id' => $this->class_course_id,
            'class_course' => new ClassCourseResource($this->whenLoaded('classCourse')),
            'submitter_id' => $this->submitter_id,
            'submitter_type' => PardisanHelper::getUserTypeByUserClass($this->submitter_type),
            'submitter' => match ($this->submitter_type){
                'App\\Models\\AdminModel' => new AdminResource($this->whenLoaded('submitter')),
                'App\\Models\\StudentModel' => new StudentCollectionResource($this->whenLoaded('submitter')),
                'App\\Models\\TeacherModel' => new TeacherResource($this->whenLoaded('submitter')),
                default => 'unknown'
            },
            'class_score_students' => ClassScoreStudentResource::collection($this->whenLoaded('classScoreStudents'))
        ];
    }
}
