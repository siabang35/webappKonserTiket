<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concert extends Model
{
    protected $fillable = [
        'name',
        'artist_id',
        'location',
        'venue',
        'date',
        'time',
        'genre',
        'image_url',
        'ticket_image',
        'tickets_left',
        'status',
        'ticket_type',
        'price',
        'description',
        'is_promotion',
        'promotion_price'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'is_promotion' => 'boolean',
        'price' => 'decimal:2',
        'promotion_price' => 'decimal:2',
        'tickets_left' => 'integer'
    ];

    /**
     * Get the artist that owns the concert.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class, 'artist_id'); // artist_id adalah kolom yang menghubungkan ke tabel artists
    }

    /**
     * Get the ticket types for the concert.
     */
    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    /**
     * Get the orders for the concert.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include upcoming concerts.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())
                    ->where('status', 'upcoming')
                    ->orderBy('date', 'asc');
    }

    /**
     * Scope a query to only include active concerts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('tickets_left', '>', 0);
    }

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get the actual price (considering promotions).
     */
    public function getActualPriceAttribute(): float
    {
        return $this->is_promotion && $this->promotion_price
            ? $this->promotion_price
            : $this->price;
    }

    /**
     * Get the formatted actual price attribute.
     */
    public function getFormattedActualPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->actual_price, 0, ',', '.');
    }

    /**
     * Check if the concert has available tickets.
     */
    public function hasAvailableTickets(): bool
    {
        return $this->tickets_left > 0;
    }

    /**
     * Get total tickets sold.
     */
    public function getTicketsSoldAttribute(): int
    {
        return $this->ticketTypes->sum(function ($ticketType) {
            return $ticketType->quantity - $ticketType->remaining_quantity;
        });
    }

    /**
     * Get total revenue from this concert.
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Check if the concert is sold out.
     */
    public function isSoldOut(): bool
    {
        return $this->tickets_left <= 0;
    }

    /**
     * Check if the concert is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->date->isFuture() && $this->status === 'upcoming';
    }
}
