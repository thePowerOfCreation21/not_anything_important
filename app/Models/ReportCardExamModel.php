<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportCardExamModel extends Model
{
    use HasFactory;

    protected $table = 'report_card_exams';

    protected $fillable = [
        'report_card_id',
        'course_id',
    ];

    public function reportCard (): BelongsTo
    {
        return $this->belongsTo(ReportCardModel::class, 'report_card_id', 'id');
    }

    public function course (): BelongsTo
    {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }

    public function reportCardExamScores (): HasMany
    {
        return $this->hasMany(ReportCardExamScoreModel::class, 'report_card_exam_id', 'id');
    }
}
