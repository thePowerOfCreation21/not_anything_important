<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ClassFileModel extends Model
{
    use HasFactory;

    protected $table = 'class_files';

    protected $fillable = [
        'author_type',
        'author_id',
        'class_course_id',
        'class_id',
        'title',
        'description',
        'file',
        'size',
        'educational_year'
    ];

    public function author (): MorphTo
    {
        return $this->morphTo();
    }

    public function classModel (): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }

    public function classCourse (): BelongsTo
    {
        return $this->belongsTo(ClassCourseModel::class, 'class_course_id', 'id');
    }
}
