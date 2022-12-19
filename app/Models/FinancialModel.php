<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialModel extends Model
{
    use HasFactory;

    protected $table = 'financials';

    protected $fillable = [
        'title',
        'description',
        'financial_type_id',
        'amount',
        'date',
        'educational_year'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public $timestamps = false;

    public function financialType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FinancialTypeModel::class, 'financial_type_id', 'id');
    }
}
