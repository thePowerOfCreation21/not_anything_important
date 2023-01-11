<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdviceStudentPivotModel extends Model
{
    use HasFactory;

    protected  $table = 'advice_student_pivot';

    protected $fillable = [
        'advice_id',
        'student_id'
    ];

    public function advice (): BelongsTo
    {
        return $this->belongsTo(AdviceModel::class, 'advice_id', 'id');
    }

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
