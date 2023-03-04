<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCardModel extends Model
{
    use HasFactory;

    protected $table = 'report_cards';

    protected $fillable = [
        'title',
        'month',
        'class_id',
        'ratio_count'
    ];

    protected $casts = [
        'ratio_count' => 'integer'
    ];

    public function classModel (): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }
}
