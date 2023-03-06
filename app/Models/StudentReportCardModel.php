<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentReportCardModel extends Model
{
    use HasFactory;

    protected $table = 'student_report_cards';

    protected $fillable = [
        'title',
        'month',
        'educational_year',
        'total_score',
        'total_ratio',
        'rank_in_class',
        'rank_in_level',
        'report_card_id',
        'class_id',
        'student_id'
    ];

    protected $casts = [
        'total_score' => 'integer',
        'total_ratio' => 'integer',
        'rank_in_class' => 'integer',
        'rank_in_level' => 'integer',
    ];

    public function reportCard (): BelongsTo
    {
        return $this->belongsTo(ReportCardModel::class, 'report_card_id', 'id');
    }

    public function classModel (): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
