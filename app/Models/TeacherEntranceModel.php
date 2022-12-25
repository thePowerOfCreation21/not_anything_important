<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherEntranceModel extends Model
{
    use HasFactory;

    protected $table = 'teacher_entrances';

    protected $fillable = [
        'teacher_id',
        'week_day',
        'entrance',
        'exit'
    ];

    protected $casts = [
        'week_day' => 'integer',
        'entrance' => 'datetime:H:i',
        'exit' => 'datetime:H:i',
    ];

    public $timestamps = false;

    public function teacher (): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}
