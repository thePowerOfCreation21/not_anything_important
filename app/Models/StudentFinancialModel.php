<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFinancialModel extends Model
{
    use HasFactory;

    protected $table = 'student_financials';

    protected $fillable = [
        'student_id',
        'amount',
        'date',
        'paid'
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid' => 'boolean'
    ];

    public $timestamps = false;

    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
