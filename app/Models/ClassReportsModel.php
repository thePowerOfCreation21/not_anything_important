<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassReportsModel extends Model
{
    use HasFactory;

    protected $table = 'class_reports';

    protected $fillable = [
        'telegram'
    ];
}
