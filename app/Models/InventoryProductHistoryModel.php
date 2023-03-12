<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryProductHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'inventory_product_histories';

    protected $fillable = [
        'description',
        'action',
        'amount',
        'date',
        'inventory_product_id'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public $timestamps = false;

    public function inventoryProduct (): BelongsTo
    {
        return $this->belongsTo(InventoryProductModel::class, 'inventory_product_id', 'id');
    }
}
