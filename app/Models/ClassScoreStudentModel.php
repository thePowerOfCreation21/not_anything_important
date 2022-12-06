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

    public $timestamps = false;

    public function student (): HasMany
    {
        return $this->hasMany(ClassCourseModel::class, 'student_id', 'id');
    }

    public function classScore(): BelongsTo
    {
        return $this->belongsTo(ClassScoreModel::class, 'class_course_id', 'id');
    }
}
