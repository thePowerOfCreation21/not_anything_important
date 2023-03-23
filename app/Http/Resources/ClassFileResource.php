<?php

namespace App\Http\Resources;

use App\Helpers\PardisanHelper;
use App\Models\AdminModel;
use App\Models\TeacherModel;
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
        $can_be_deleted = false;
        $user = $request->user();

        if (! empty($user))
        {
            if ($user instanceof AdminModel || ($user instanceof TeacherModel && $this->author_type == "App\\Models\\TeacherModel" && $this->author_id == $user->id))
                $can_be_deleted = true;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file' => $this->file,
            'size' => $this->size,
            'educational_year' => $this->educational_year,
            'created_at' => Helpers::getCustomDateCast($this->created_at),
            'class_course_id' => $this->when(! empty($this->class_course_id), $this->class_course_id),
            'class_course' => new ClassCourseResource($this->whenLoaded('classCourse')),
            'class_id' => $this->when(! empty($this->class_id), $this->class_id),
            'class' => new ClassCourseResource($this->whenLoaded('classModel')),
            'author_id' => $this->author_id,
            'author_type' => PardisanHelper::getUserTypeByUserClass($this->author_type),
            'author' => match ($this->author_type){
                'App\\Models\\AdminModel' => new AdminResource($this->whenLoaded('author')),
                'App\\Models\\StudentModel' => new StudentCollectionResource($this->whenLoaded('author')),
                'App\\Models\\TeacherModel' => new TeacherResource($this->whenLoaded('author')),
                default => 'unknown'
            },
            'can_be_deleted' => $can_be_deleted
        ];
    }
}
