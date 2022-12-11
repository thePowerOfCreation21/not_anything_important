<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $table = 'financials';

    protected $fillable = [
        'title',
        'description',
        'financial_type_id',
        'amount',
        'date'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public $timestamps = false;
}
