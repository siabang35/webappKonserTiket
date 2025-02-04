<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'payment_details',
        'payment_url',
        'expiry_time',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'expiry_time' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REFUNDED = 'refunded';

    // Payment method constants
    const METHOD_CREDIT_CARD = 'credit_card';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_E_WALLET = 'e_wallet';

    public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}


    public function isExpired()
    {
        return $this->expiry_time && now()->isAfter($this->expiry_time);
    }

    public function markAsExpired()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
            'payment_details' => array_merge(
                $this->payment_details ?? [],
                ['expired_at' => now()]
            )
        ]);
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            self::METHOD_CREDIT_CARD => 'Kartu Kredit',
            self::METHOD_BANK_TRANSFER => 'Transfer Bank',
            self::METHOD_E_WALLET => 'E-Wallet',
            default => ucfirst($this->payment_method)
        };
    }
}
