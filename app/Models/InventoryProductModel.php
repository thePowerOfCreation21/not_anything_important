<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
