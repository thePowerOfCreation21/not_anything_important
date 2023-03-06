<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentReportCardScoreModel extends Model
{
    use HasFactory;

    protected $table = 'student_report_card_scores';

    protected $fillable = [
        'score',
        'highest_score_in_class',
        'rank_in_class',
        'rank_in_level',
        'has_star',
        'course_id',
        'student_report_card_id'
    ];

    protected $casts = [
        'score' => 'integer',
        'highest_score_in_class' => 'integer',
        'rank_in_class' => 'integer',
        'rank_in_level' => 'integer',
        'has_star' => 'boolean'
    ];

    public function course (): BelongsTo
    {
        return $this->belongsTo(CourseModel::class, 'course_id','id');
    }

    public function studentReportCard (): BelongsTo
    {
        return $this->belongsTo(StudentReportCardModel::class, 'student_report_card_id', 'id');
    }
}
