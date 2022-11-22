<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralStatisticModel extends Model
{
    use HasFactory;

    protected $table = 'general_statistics';

    protected $fillable = [
        'label',
        'paid',
        'not_paid',
        'educational_year'
    ];

    protected $casts = [
        'paid' => 'integer',
        'not_paid' => 'integer'
    ];
}
