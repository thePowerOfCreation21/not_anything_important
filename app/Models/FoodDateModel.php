<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodDateModel extends Model
{
    use HasFactory;

    protected $table = 'food_dates';

    protected $fillable = [
        'date'
    ];

    public $timestamps = false;

    public function Foods (): BelongsToMany
    {
        return $this->belongsToMany(FoodModel::class, 'food_date_food_pivot', 'food_date_id', 'food_id');
    }
}
