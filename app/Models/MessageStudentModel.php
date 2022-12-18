<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageStudentModel extends Model
{
    use HasFactory;

    protected $table = 'message_students';

    protected $fillable = [
        'student_id',
        'message_id',
        'is_seen'
    ];

    protected $casts = [
        'is_seen' => 'boolean'
    ];
}
