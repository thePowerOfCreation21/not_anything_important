<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherEntranceHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'teacher_entrance_histories';

    protected $fillable = [
        'teacher_id',
        'week_day',
        'entrance',
        'exit',
        'late_string',
        'date'
    ];

    public $timestamps = false;

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}
