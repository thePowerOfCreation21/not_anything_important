<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdviceHourModel extends Model
{
    use HasFactory;

    protected $table = 'advice_hours';

    protected $fillable = [
        'hour'
    ];

    public $timestamps = false;
}
