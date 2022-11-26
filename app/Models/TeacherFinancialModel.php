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
        'educational_year'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public $timestamps = false;
}
