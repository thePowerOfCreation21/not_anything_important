<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDateModel extends Model
{
    use HasFactory;

    protected $table = 'food_dates';

    protected $fillable = [
        'date'
    ];

    public $timestamps = false;
}
