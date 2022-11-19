<?php

namespace App\Models;

use App\Casts\CustomDateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
