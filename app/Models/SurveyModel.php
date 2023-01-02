<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyModel extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'teacher_id',
        'text',
        'is_active',
        'is_template'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_template' => 'boolean'
    ];

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}
