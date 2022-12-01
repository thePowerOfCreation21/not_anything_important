<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseModel extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'ratio'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function classes (): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_course', 'course_id', 'class_id');
    }
}
