<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyOptionModel extends Model
{
    use HasFactory;

    protected $table = 'survey_options';

    protected $fillable = [
        'survey_id',
        'title',
        'participants_count'
    ];

    public $timestamps = false;

    public function survey (): BelongsTo
    {
        return $this->belongsTo(SurveyModel::class, 'survey_id', 'id');
    }

    public function surveyAnswers (): HasMany
    {
        return $this->hasMany(SurveyAnswerModel::class, 'survey_option_id', 'id');
    }
}
