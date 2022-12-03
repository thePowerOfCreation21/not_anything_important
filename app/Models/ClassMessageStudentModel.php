<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassMessageStudentModel extends Model
{
    use HasFactory;

    protected $table = 'class_message_student';

    protected $fillable = [
        'student_id',
        'class_message_id',
        'is_seen'
    ];

    protected $casts = [
        'is_seen' => 'boolean'
    ];

    public $timestamps = false;

    public function classMessage (): BelongsTo
    {
        return $this->belongsTo(ClassMessageModel::class, 'class_message_id', 'id');
    }
}
