<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceModel extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'class_id',
        'course_id',
        'date',
        'educational_year'
    ];
}
