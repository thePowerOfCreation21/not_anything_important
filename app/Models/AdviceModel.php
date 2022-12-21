<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdviceModel extends Model
{
    use HasFactory;

    protected $table = 'advices';

    protected $fillable = [
        'hour',
        'date',
        'student_id',
        'status'
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
