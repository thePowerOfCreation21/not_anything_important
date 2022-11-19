<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'wallet_histories';

    protected $fillable = [
        'charged_by_id',
        'student_id',
        'amount',
        'action',
        'status'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public function Student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }
}
