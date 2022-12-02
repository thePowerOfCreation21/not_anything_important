<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function attendanceStudents (): BelongsTo
    {
        return $this->belongsTo(AttendanceStudentModel::class, 'attendance_id', 'id');
    }
}
