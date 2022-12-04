<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassScoreStudentModel extends Model
{
    use HasFactory;

    protected $table = 'class_score_students';

    protected $fillable = [
        'class_score_id',
        'student_id',
        'score'
    ];

    public function classScoreStudents (): HasMany
    {
        return $this->hasMany(ClassScoreStudentModel::class, 'class_score_id', 'id');
    }

    public function classCourse (): BelongsTo
    {
        return $this->belongsTo(ClassCourseModel::class, 'class_course_id', 'id');
    }
}
