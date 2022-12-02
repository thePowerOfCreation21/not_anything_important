<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    public function attendance (): BelongsTo
    {
        return $this->belongsTo(AttendanceModel::class, 'attendance_id', 'id');
    }
}
