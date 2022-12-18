<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTypeModel extends Model
{
    use HasFactory;

    protected $table = 'financial_types';

    protected $fillable = [
        'title'
    ];
}
