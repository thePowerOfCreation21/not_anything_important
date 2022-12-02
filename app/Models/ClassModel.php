<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * @return HasMany
     */
    public function classCourses ()
    {
        return $this->hasMany(ClassCourseModel::class, 'class_id', 'id');
    }
}
