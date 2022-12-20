<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryProductModel extends Model
{
    use HasFactory;

    protected $table = 'inventory_products';

    protected $fillable = [
        'title',
        'code',
        'stock',
        'description'
    ];

    protected $casts = [
        'stock' => 'integer'
    ];

    public function inventoryProductHistories (): HasMany
    {
        return $this->hasMany(InventoryProductHistoryModel::class, 'inventory_product_id', 'id');
    }
}
