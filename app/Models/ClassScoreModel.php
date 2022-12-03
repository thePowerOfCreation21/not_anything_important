<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassScoreModel extends Model
{
    use HasFactory;

    protected $table = 'class_score';

    protected $fillable = [
        'class_course_id',
        'date',
        'educational_year',
        'max_score'
    ];
}
