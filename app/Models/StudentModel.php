<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class StudentModel extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'students';

    // DO NOT EVENT THINK ABOUT CHANGING THIS BECAUSE IF YOU MESS UP, FIXING IT WOULD BE NIGHTMARE -bahman
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'meli_code',
        'birth_certificate_number',
        'birth_certificate_serie_number',
        'birth_certificate_serial_number',
        'birth_certificate_issued_location',
        'birth_location',
        'birth_date',
        'birth_place',
        'nationality',
        'religion',
        'religion_orientation',
        'illness_record',
        'medicine_in_use',
        'family_child_number',
        'all_family_children_count',
        'is_disabled',
        'divorced_parents',
        'dominant_hand',
        'living_with',
        'address',
        'mobile_number',
        'phone_number',
        'phone_number_of_close_relative',
        'father_first_name',
        'father_last_name',
        'father_father_name',
        'father_birth_certificate_number',
        'father_birth_certificate_serie_number',
        'father_birth_certificate_issued_location',
        'father_birth_location',
        'father_birth_date',
        'father_nationality',
        'father_religion',
        'father_religion_orientation',
        'father_meli_code',
        'father_education',
        'father_job',
        'father_health_status',
        'father_mobile_number',
        'father_work_address',
        'father_file',
        'father_educational_level',
        'mother_first_name',
        'mother_last_name',
        'mother_father_name',
        'mother_birth_certificate_number',
        'mother_birth_certificate_serie_number',
        'mother_birth_certificate_issued_location',
        'mother_birth_location',
        'mother_birth_date',
        'mother_nationality',
        'mother_religion',
        'mother_religion_orientation',
        'mother_meli_code',
        'mother_education',
        'mother_job',
        'mother_health_status',
        'mother_mobile_number',
        'mother_work_address',
        'mother_file',
        'mother_educational_level',
        'non_contagious_illness',
        'mental_illness',
        'level',
        'file',
        'report_card_pdf',
        'father_birth_place',
        'mother_birth_place',
        'educational_year',
        'wallet_amount',
        'is_block',
        'reason_for_blocking',
        'should_change_password',
        'password',
        'register_status',
        'last_time_sms_sent',
        'otp_expires_at',
        'otp'
    ];

    protected $hidden = [
        'password',
        'otp',
        'otp_expires_at'
    ];

    protected $casts = [
        'wallet_amount' => 'integer',
        'last_time_sms_sent' => 'integer',
        'should_change_password' => 'boolean',
        'otp_expires_at' => 'integer'
    ];

    public function financials (): HasMany
    {
        return $this->hasMany(StudentFinancialModel::class, 'student_id', 'id');
    }

    public function classes (): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id');
    }
}
