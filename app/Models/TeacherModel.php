<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TeacherModel extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'teachers';

    protected $fillable = [
        'full_name',
        'father_name',
        'birth_certificate_number',
        'national_id',
        'birth_certificate_location',
        'birth_location',
        'birth_date',
        'degree_of_education',
        'address',
        'phone_number',
        'partner_full_name',
        'partner_job',
        'partner_birth_certificate_number',
        'partner_national_id',
        'partner_birth_certificate_location',
        'partner_birth_location',
        'partner_birth_date',
        'partner_degree_of_education',
        'partner_job_address',
        'partner_phone_number',
        'partner_emergency_call_number',
        'is_married',
        'password',
        'register_status',
        'educational_year',
    ];
}
