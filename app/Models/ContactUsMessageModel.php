<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsMessageModel extends Model
{
    use HasFactory;

    protected $table = 'contact_us_messages';

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'text',
        'is_seen'
    ];

    protected $casts = [
        'is_seen' => 'boolean'
    ];
}
