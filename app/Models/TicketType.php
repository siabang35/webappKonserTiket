<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TicketType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'concert_id',
        'name',
        'price',
        'quantity',
        'description',
        'benefits',
        'max_per_transaction',
        'sale_starts_at',
        'sale_ends_at',
        'is_active',
        'metadata'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'sale_starts_at',
        'sale_ends_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'concert_id' => 'integer',
        'max_per_transaction' => 'integer',
        'is_active' => 'boolean',
        'benefits' => 'array',
        'metadata' => 'array'
    ];

    protected $appends = [
        'available_quantity',
        'sold_quantity',
        'reserved_quantity',
        'is_on_sale',
        'sale_status'
    ];

    // Relationships
    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('sale_starts_at')
                          ->orWhere('sale_starts_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('sale_ends_at')
                          ->orWhere('sale_ends_at', '>', now());
                    });
    }

    // Accessors
    public function getAvailableQuantityAttribute()
    {
        return $this->tickets()->available()->count();
    }

    public function getSoldQuantityAttribute()
    {
        return $this->tickets()->sold()->count();
    }

    public function getReservedQuantityAttribute()
    {
        return $this->tickets()->reserved()->count();
    }

    public function getIsOnSaleAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->sale_starts_at && $now->isBefore($this->sale_starts_at)) {
            return false;
        }

        if ($this->sale_ends_at && $now->isAfter($this->sale_ends_at)) {
            return false;
        }

        return true;
    }

    public function getSaleStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = now();

        if ($this->sale_starts_at && $now->isBefore($this->sale_starts_at)) {
            return 'upcoming';
        }

        if ($this->sale_ends_at && $now->isAfter($this->sale_ends_at)) {
            return 'ended';
        }

        if ($this->available_quantity === 0) {
            return 'sold_out';
        }

        return 'on_sale';
    }

    // Helper Methods
    public function hasAvailableTickets(int $quantity = 1): bool
    {
        return $this->is_on_sale && $this->available_quantity >= $quantity;
    }

    public function createTickets(): void
    {
        DB::transaction(function () {
            if ($this->quantity > 0) {
                $tickets = [];
                for ($i = 0; $i < $this->quantity; $i++) {
                    $tickets[] = [
                        'concert_id' => $this->concert_id,
                        'price' => $this->price,
                        'status' => Ticket::STATUS_AVAILABLE,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                foreach (array_chunk($tickets, 1000) as $chunk) {
                    $this->tickets()->insert($chunk);
                }
            }
        });
    }

    public function reserveTickets(int $quantity): array
    {
        if (!$this->hasAvailableTickets($quantity)) {
            return [];
        }

        return DB::transaction(function () use ($quantity) {
            $tickets = $this->tickets()
                ->available()
                ->limit($quantity)
                ->lockForUpdate()
                ->get();

            $reservedTickets = [];
            foreach ($tickets as $ticket) {
                if ($ticket->reserve()) {
                    $reservedTickets[] = $ticket;
                }
            }

            return $reservedTickets;
        });
    }

    public function validatePurchaseQuantity(int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
        }

        if ($this->max_per_transaction && $quantity > $this->max_per_transaction) {
            return false;
        }

        return $this->hasAvailableTickets($quantity);
    }
}
