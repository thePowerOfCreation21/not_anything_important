<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImageModel extends Model
{
    use HasFactory;

    protected $table = 'gallery_images';

    protected $fillable = [
        'image'
    ];

    public $timestamps = false;
}
