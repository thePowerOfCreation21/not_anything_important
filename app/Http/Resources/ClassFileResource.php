<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassFileResource extends JsonResource
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
            'file' => $this->file,
            'size' => $this->size,
            'created_at' => Helpers::getCustomDateCast($this->created_at),
            'class_course_id' => $this->when(! empty($this->class_course_id), $this->class_course_id),
            'class_course' => new ClassCourseResource($this->whenLoaded('classCourse')),
            'class_id' => $this->when(! empty($this->class_id), $this->class_id),
            'class' => new ClassCourseResource($this->whenLoaded('classModel')),
            'author_id' => $this->author_id,
            'author_type' => match ($this->author_type){
                'App\\Models\\AdminModel' => 'admin',
                'App\\Models\\StudentModel' => 'student',
                'App\\Models\\TeacherModel' => 'teacher',
                default => 'unknown'
            },
            'author' => match ($this->author_type){
                'App\\Models\\AdminModel' => new AdminResource($this->whenLoaded('author')),
                'App\\Models\\StudentModel' => new StudentCollectionResource($this->whenLoaded('author')),
                'App\\Models\\TeacherModel' => new TeacherResource($this->whenLoaded('author')),
                default => 'unknown'
            }
        ];
    }
}
