<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'title'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
