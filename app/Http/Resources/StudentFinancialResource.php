<?php

namespace App\Http\Resources;

use App\Helpers\PardisanHelper;
use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentFinancialResource extends JsonResource
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
            'payment_type' => $this->payment_type,
            'check_number' => $this->check_number,
            'receipt_number' => $this->receipt_number,
            'amount' => $this->amount,
            'paid' => $this->paid,
            'date' => Helpers::getCustomDateCast($this->date),
            'payment_date' => Helpers::getCustomDateCast($this->payment_date),
            'payment_status' => PardisanHelper::getStudentFinancialPaymentStatus($this),
            'educational_year' => $this->educational_year,
            'check_image' => $this->check_image,
            'student_id' => $this->student_id,
            'student' => new StudentResource($this->whenLoaded('student'))
        ];
    }
}
