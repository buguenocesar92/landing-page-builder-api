<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Landing extends Model
{
    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'slug',
        'description',
        'content',
        'custom_domain',
        'is_active',
        'views_count',
        'leads_count',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'leads_count' => 'integer',
    ];

    /**
     * Get the user that owns the landing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template used by this landing.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the leads for this landing.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Scope for active landings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the URL of the landing page.
     */
    public function getUrlAttribute(): string
    {
        if ($this->custom_domain) {
            return "https://{$this->custom_domain}";
        }
        
        return url("/l/{$this->slug}");
    }

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Increment the leads count.
     */
    public function incrementLeads(): void
    {
        $this->increment('leads_count');
    }
} 