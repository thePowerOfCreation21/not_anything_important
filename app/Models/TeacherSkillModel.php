<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSkillModel extends Model
{
    use HasFactory;

    protected $table = 'teacher_skill';

    protected $fillable = [
        'teacher_id',
        'course_title',
        'educational_institution',
        'skill_level'
    ];

    public $timestamps = false;
}
