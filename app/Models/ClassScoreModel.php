<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function classScoreStudents (): HasMany
    {
        return $this->hasMany(ClassScoreStudentModel::class, 'class_score_id', 'id');
    }

    public function classCourse (): BelongsTo
    {
        return $this->belongsTo(ClassCourseModel::class, 'class_course_id', 'id');
    }
}
