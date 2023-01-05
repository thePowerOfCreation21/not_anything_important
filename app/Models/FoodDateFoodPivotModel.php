<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodDateFoodPivotModel extends Model
{
    use HasFactory;

    protected $table = 'food_date_food_pivot';

    protected $fillable = [
        'food_date_id',
        'food_id'
    ];

    public $timestamps = false;

    public function foodDate (): BelongsTo
    {
        return $this->belongsTo(FoodDateModel::class, 'food_date_id', 'id');
    }

    public function food (): BelongsTo
    {
        return $this->belongsTo(FoodModel::class, 'food_id', 'id');
    }
}
