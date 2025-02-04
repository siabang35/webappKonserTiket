<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'concert_id',
        'ticket_type_id',
        'user_id',
        'price',
        'status',
        'seat_number',
        'ticket_code',
        'serial_number', // Added serial number field
        'qr_code',
        'is_scanned',
        'scanned_at',
        'valid_until',
        'metadata'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'scanned_at',
        'valid_until'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'concert_id' => 'integer',
        'ticket_type_id' => 'integer',
        'user_id' => 'integer',
        'is_scanned' => 'boolean',
        'metadata' => 'array'
    ];

    protected $appends = [
        'status_label',
        'is_valid',
        'can_be_transferred'
    ];

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_SOLD = 'sold';
    const STATUS_RESERVED = 'reserved';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    const STATUS_USED = 'used';

    // Boot method to automatically generate serial number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->serial_number = $ticket->generateSerialNumber();
        });
    }

    // Generate unique serial number
    protected function generateSerialNumber()
    {
        $prefix = 'TIX';
        $year = date('Y');
        $month = date('m');

        // Get the last ticket number for this month
        $lastTicket = static::where('serial_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('serial_number', 'desc')
            ->first();

        if ($lastTicket) {
            // Extract the numeric part and increment
            $lastNumber = intval(substr($lastTicket->serial_number, -6));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format: TIX202403000001 (TIX + Year + Month + 6-digit sequence)
        return $prefix . $year . $month . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transfers()
    {
        return $this->hasMany(TicketTransfer::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }

    public function scopeReserved($query)
    {
        return $query->where('status', self::STATUS_RESERVED);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED, self::STATUS_USED]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_SOLD => 'Terjual',
            self::STATUS_RESERVED => 'Dipesan',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_USED => 'Terpakai',
            default => 'Unknown'
        };
    }

    public function getIsValidAttribute(): bool
    {
        return !$this->is_scanned &&
               $this->status === self::STATUS_SOLD &&
               ($this->valid_until === null || $this->valid_until->isFuture());
    }

    public function getCanBeTransferredAttribute(): bool
    {
        return $this->is_valid &&
               $this->concert->date->isFuture() &&
               !$this->transfers()->pending()->exists();
    }

    // Helper Methods
    public function generateTicketCode(): void
    {
        $this->ticket_code = strtoupper(Str::random(8));
        $this->qr_code = $this->generateQRCode();
        $this->save();
    }

    public function generateQRCode(): string
    {
        return base64_encode(json_encode([
            'ticket_id' => $this->id,
            'serial_number' => $this->serial_number,
            'ticket_code' => $this->ticket_code,
            'concert_id' => $this->concert_id,
            'user_id' => $this->user_id,
            'timestamp' => time()
        ]));
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD;
    }

    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    public function reserve(): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_RESERVED,
            'metadata' => array_merge($this->metadata ?? [], [
                'reserved_at' => now()->toIso8601String()
            ])
        ]);
    }

    public function sell(User $user): bool
    {
        if (!$this->isAvailable() && !$this->isReserved()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_SOLD,
            'user_id' => $user->id,
            'valid_until' => $this->concert->date->addDays(1),
            'metadata' => array_merge($this->metadata ?? [], [
                'sold_at' => now()->toIso8601String()
            ])
        ]);

        $this->generateTicketCode();

        return true;
    }

    public function release(): bool
    {
        if ($this->isSold()) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_AVAILABLE,
            'user_id' => null,
            'metadata' => array_merge($this->metadata ?? [], [
                'released_at' => now()->toIso8601String()
            ])
        ]);
    }

    public function scan(): bool
    {
        if (!$this->is_valid) {
            return false;
        }

        return $this->update([
            'is_scanned' => true,
            'scanned_at' => now(),
            'status' => self::STATUS_USED,
            'metadata' => array_merge($this->metadata ?? [], [
                'scanned_at' => now()->toIso8601String()
            ])
        ]);
    }

    public function cancel(): bool
    {
        if (!in_array($this->status, [self::STATUS_SOLD, self::STATUS_RESERVED])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_CANCELLED,
            'metadata' => array_merge($this->metadata ?? [], [
                'cancelled_at' => now()->toIso8601String()
            ])
        ]);
    }

    public function transfer(User $newUser): bool
    {
        if (!$this->can_be_transferred) {
            return false;
        }

        $oldUser = $this->user;

        $transfer = $this->transfers()->create([
            'from_user_id' => $oldUser->id,
            'to_user_id' => $newUser->id,
            'status' => 'pending'
        ]);

        return (bool) $transfer;
    }
}
