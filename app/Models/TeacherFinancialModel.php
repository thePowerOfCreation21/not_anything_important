<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherFinancialModel extends Model
{
    use HasFactory;

    protected $table = 'teacher_financials';

    protected $fillable = [
        'teacher_id',
        'amount',
        'date',
        'educational_year',
        'receipt_image',
        'description'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public $timestamps = false;

    public function teacher (): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'id');
    }
}
