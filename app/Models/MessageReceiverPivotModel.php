<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MessageReceiverPivotModel extends Model
{
    use HasFactory;

    protected $table = 'message_receiver_pivot';

    protected $fillable = [
        'message_id',
        'receiver_type',
        'receiver_id',
        'is_seen'
    ];

    protected $casts = [
        'is_seen' => 'boolean'
    ];

    public $timestamps = false;

    public function message (): BelongsTo
    {
        return $this->belongsTo(MessageModel::class, 'message_id', 'id');
    }

    public function receiver (): MorphTo
    {
        return $this->morphTo();
    }
}
