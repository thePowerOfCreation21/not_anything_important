<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherFinancialResource extends JsonResource
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
            'amount' => $this->amount,
            'date' => Helpers::getCustomDateCast($this->date),
            'educational_year' => $this->educational_year,
            'receipt_image' => $this->receipt_image,
            'description' => $this->description,
            'teacher_id' => $this->teacher_id,
            'teacher' => new TeacherResource($this->whenLoaded('teacher'))
        ];
    }
}
