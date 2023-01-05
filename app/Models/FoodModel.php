<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodModel extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'title',
        'price'
    ];

    protected $casts = [
        'price' => 'integer'
    ];

    public function foodDates (): BelongsToMany
    {
        return $this->belongsToMany(FoodDateModel::class, 'food_date_food_pivot', 'food_id', 'food_date_id');
    }
}
