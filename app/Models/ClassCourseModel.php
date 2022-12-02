<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassCourseModel extends Model
{
    use HasFactory;

    protected $table = 'class_course';

    protected $fillable = [
        'class_id',
        'course_id',
        'teacher_id'
    ];

    public $timestamps = false;

    public function classModel (): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }

    public function course (): BelongsTo
    {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}
