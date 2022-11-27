<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDisciplineModel extends Model
{
    use HasFactory;

    protected $table = 'student_disciplines';

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'date',
        'educational_year'
    ];

    public $timestamps = false;

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
