<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDisciplineModel extends Model
{
    use HasFactory;

    protected $table = 'student_disciplines';

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'date',
        'educational_year'
    ];

    public $timestamps = false;
}
