<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }

    public function SurveyOptions (): HasMany
    {
        return $this->hasMany(SurveyOptionModel::class, 'survey_id', 'id');
    }
}
