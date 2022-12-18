<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdviceDateModel extends Model
{
    use HasFactory;

    protected $table = 'advice_dates';

    protected $fillable = [
        'date',
        'educational_date'
    ];

    public $timestamps = false;
}
