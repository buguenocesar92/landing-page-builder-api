<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'landing_id',
        'name',
        'email',
        'phone',
        'message',
        'extra_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'extra_data' => 'array',
    ];

    /**
     * Get the landing that this lead belongs to.
     */
    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class);
    }

    /**
     * Scope for recent leads.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the full name.
     */
    public function getFullContactAttribute(): string
    {
        $contact = $this->name;
        if ($this->phone) {
            $contact .= " ({$this->phone})";
        }
        return $contact;
    }
} 