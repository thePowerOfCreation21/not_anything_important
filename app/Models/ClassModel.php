<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'title',
        'level',
        'educational_year'
    ];

    /**
     * @return BelongsToMany
     */
    public function students (): BelongsToMany
    {
        return $this->belongsToMany(StudentModel::class, 'class_student', 'class_id', 'student_id');
    }

    public function courses (): BelongsToMany
    {
        return $this->belongsToMany(CourseModel::class, 'class_course', 'class_id', 'course_id');
    }
}
