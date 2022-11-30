<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherWorkExperienceModel extends Model
{

    use HasFactory;
    protected $table = 'teacher_work_experiences';

    protected $fillable = [
        'teacher_id',
        'title',
        'workplace_name',
        'work_title',
        'current_status',
        'reason_for_the_termination_of_cooperation',
        'workplace_location',
    ];

    public $timestamps = false;
}
