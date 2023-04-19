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
        'text',
        'participants_count',
        'survey_category_id'
    ];

    public function surveyCategory (): BelongsTo
    {
        return $this->belongsTo(SurveyCategoryModel::class, 'survey_category_id', 'id');
    }

    public function surveyOptions (): HasMany
    {
        return $this->hasMany(SurveyOptionModel::class, 'survey_id', 'id');
    }
}
