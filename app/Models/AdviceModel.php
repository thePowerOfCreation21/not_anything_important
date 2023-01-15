<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdviceModel extends Model
{
    use HasFactory;

    protected $table = 'advices';

    protected $fillable = [
        'advice_hour_id',
        'advice_date_id',
        'student_id',
        'status',
        'educational_year'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    public function adviceDate(): BelongsTo
    {
        return $this->belongsTo(AdviceDateModel::class, 'advice_date_id', 'id');
    }

    public function adviceHour(): BelongsTo
    {
        return $this->belongsTo(AdviceHourModel::class, 'advice_hour_id', 'id');
    }
}
