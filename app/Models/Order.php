<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'concert_id',
        'ticket_type',
        'ticket_count',
        'total_amount',
        'total_price',
        'status',
        'ticket_code'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'ticket_count' => 'integer',
        'ticket_details' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_FAILED = 'failed';

    // Ticket type constants
    const TYPE_REGULAR = 'regular';
    const TYPE_VIP = 'vip';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function payment()
{
    return $this->hasOne(Payment::class, 'order_id', 'order_id');
}


    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REFUNDED => 'secondary',
            self::STATUS_FAILED => 'danger',
            default => 'light'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REFUNDED => 'Dikembalikan',
            self::STATUS_FAILED => 'Gagal',
            default => 'Unknown'
        };
    }

    public function generateTicketCode()
    {
        return strtoupper(uniqid('TIX-') . substr(md5($this->id . time()), 0, 6));
    }

    public function isExpired()
    {
        return $this->status === self::STATUS_PENDING &&
               $this->created_at->addHours(24)->isPast();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }
}
