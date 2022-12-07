<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassReportsModel extends Model
{
    use HasFactory;

    protected $table = 'class_reports';

    protected $fillable = [
        'date',
        'period',
        'class_course_id',
        'report'
    ];

    protected $casts = [
        'period' => 'integer',
    ];

    public $timestamps = false;

    public function classCourse (): BelongsTo
    {
        return $this->belongsTo(ClassCourseModel::class, 'class_course_id', 'id');
    }
}
