<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFinancialModel extends Model
{
    use HasFactory;

    protected $table = 'student_financials';

    protected $fillable = [
        'student_id',
        'payment_type',
        'check_number',
        'receipt_number',
        'amount',
        'date',
        'payment_date',
        'educational_year',
        'paid',
        'check_image'
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public $timestamps = false;

    // relations
    public function student (): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    // scopes
    public function scopeCanSendSms ($q, bool $canSendSms = true): mixed
    {
        $condition = $canSendSms ? 'where' : 'whereNot';
        return $q
            ->$condition(function ($q){
                $q
                    ->where('paid', false)
                    ->whereDate('payment_date', '<=', date('Y-m-d', time() + (86400 * 7)));
            });
    }
}
