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
        'student_id',
        'survey_option_id'
    ];

    public $timestamps = false;

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    public function surveyOption (): BelongsTo
    {
        return $this->belongsTo(SurveyOptionModel::class, 'survey_option_id', 'id');
    }
}
