<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassScoreStudentModel extends Model
{
    use HasFactory;

    protected $table = 'class_score_students';

    protected $fillable = [
        'class_score_id',
        'student_id',
        'score'
    ];
}
