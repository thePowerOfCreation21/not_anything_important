<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'file',
        'size'
    ];

    public function author (): MorphTo
    {
        return $this->morphTo();
    }
}
