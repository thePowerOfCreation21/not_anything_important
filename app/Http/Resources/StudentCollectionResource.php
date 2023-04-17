<?php

namespace App\Http\Resources;

use Genocide\Radiocrud\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /*
         * {
            "id": 1,
            "first_name": "test user",
            "last_name": "last name",
            "full_name": "test user last name",
            "meli_code": "1234567",
            "birth_certificate_number": null,
            "birth_certificate_serie_number": null,
            "birth_certificate_serial_number": null,
            "birth_certificate_issued_location": null,
            "birth_location": null,
            "birth_date": null,
            "nationality": null,
            "religion": null,
            "religion_orientation": null,
            "illness_record": null,
            "medicine_in_use": null,
            "family_child_number": null,
            "all_family_children_count": null,
            "is_disabled": null,
            "divorced_parents": null,
            "dominant_hand": null,
            "living_with": null,
            "address": null,
            "mobile_number": null,
            "phone_number": null,
            "phone_number_of_close_relative": null,
            "father_first_name": null,
            "father_last_name": null,
            "father_father_name": null,
            "father_birth_certificate_number": null,
            "father_birth_certificate_serie_number": null,
            "father_birth_certificate_serial_number": null,
            "father_birth_certificate_issued_location": null,
            "father_birth_location": null,
            "father_birth_date": null,
            "father_nationality": null,
            "father_religion": null,
            "father_religion_orientation": null,
            "father_meli_code": null,
            "father_education": null,
            "father_job": null,
            "father_health_status": null,
            "father_mobile_number": null,
            "father_work_address": null,
            "father_file": null,
            "mother_first_name": null,
            "mother_last_name": null,
            "mother_father_name": null,
            "mother_birth_certificate_number": null,
            "mother_birth_certificate_serie_number": null,
            "mother_birth_certificate_serial_number": null,
            "mother_birth_certificate_issued_location": null,
            "mother_birth_location": null,
            "mother_birth_date": null,
            "mother_nationality": null,
            "mother_religion": null,
            "mother_religion_orientation": null,
            "mother_meli_code": null,
            "mother_education": null,
            "mother_job": null,
            "mother_health_status": null,
            "mother_mobile_number": null,
            "mother_work_address": null,
            "mother_file": null,
            "non_contagious_illness": null,
            "mental_illness": null,
            "level": null,
            "file": null,
            "report_card_pdf": null,
            "educational_year": "1401",
            "wallet_amount": 0,
            "is_block": 0,
            "reason_for_blocking": null,
            "should_change_password": 0,
            "password": "12345678",
            "register_status": "added_by_admin",
            "created_at": "2022-11-23T08:04:14.000000Z",
            "updated_at": "2022-11-23T08:04:14.000000Z"
        }
         */
        return [
            'id' => $this->id,
            'wallet_amount' => $this->wallet_amount,
            'meli_code' => $this->meli_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'father_first_name' => $this->father_first_name,
            'father_last_name' => $this->father_last_name,
            'father_father_name' => $this->father_father_name,
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'father_mobile_number' => $this->father_mobile_number,
            'mother_mobile_number' => $this->father_mobile_number,
            'level' => $this->level,
            'educational_year' => $this->educational_year,
            'register_status' => $this->register_status,
            'should_change_password' => $this->should_change_password,
            'created_at' => Helpers::getCustomDateCast($this->created_at)
        ];
    }
}
