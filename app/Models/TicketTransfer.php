<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'from_user_id',
        'to_user_id',
        'status',
        'transfer_code',
        'expires_at',
        'completed_at'
    ];

    protected $dates = [
        'expires_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'from_user_id' => 'integer',
        'to_user_id' => 'integer'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
