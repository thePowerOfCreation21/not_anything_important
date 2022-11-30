<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStudentModel extends Model
{
    use HasFactory;

    protected $table = 'attendance_student';

    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
        'late'
    ];

    protected $casts = [
        'late' => 'integer'
    ];

    public $timestamps = false;
}
