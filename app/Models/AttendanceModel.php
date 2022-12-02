<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceModel extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'class_course_id',
        'date',
        'educational_year'
    ];

    public function attendanceStudents (): HasMany
    {
        return $this->hasMany(AttendanceStudentModel::class, 'attendance_id', 'id');
    }

    public function classCourse (): BelongsTo
    {
        return $this->belongsTo(ClassCourseModel::class, 'class_course_id', 'id');
    }
}
