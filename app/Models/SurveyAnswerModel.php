<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswerModel extends Model
{
    use HasFactory;

    protected $table = 'survey_answers';

    protected $fillable = [
        'participant_type',
        'participant_id',
        'survey_option_id',
        'survey_category_id',
        'survey_id'
    ];

    public $timestamps = false;

    public function participant (): BelongsTo
    {
        return $this->morphTo();
    }

    public function surveyOption (): BelongsTo
    {
        return $this->belongsTo(SurveyOptionModel::class, 'survey_option_id', 'id');
    }
}
