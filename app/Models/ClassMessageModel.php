<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassMessageModel extends Model
{
    use HasFactory;

    protected $table = 'class_messages';

    protected $fillable = [
        'student_id',
        'class_id',
        'text'
    ];
}
