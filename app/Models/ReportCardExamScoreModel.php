<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCardExamScoreModel extends Model
{
    use HasFactory;

    protected $table = 'report_card_exam_scores';

    protected $fillable = [
        'report_card_exam_id',
        'student_id',
        'score',
        'is_present'
    ];

    protected $casts = [
        'is_present' => 'boolean'
    ];

    public function reportCardExamModel (): BelongsTo
    {
        return $this->belongsTo(ReportCardExamModel::class, 'report_card_exam_id', 'id');
    }

    public function studentModel (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
