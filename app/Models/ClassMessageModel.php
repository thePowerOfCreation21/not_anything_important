<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassMessageModel extends Model
{
    use HasFactory;

    protected $table = 'class_messages';

    protected $fillable = [
        'student_id',
        'class_id',
        'text'
    ];

    public function classMessageStudents (): HasMany
    {
        return $this->hasMany(ClassMessageStudentModel::class, 'class_message_id', 'id');
    }
}
