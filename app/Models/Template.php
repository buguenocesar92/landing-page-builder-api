<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = [
        'name',
        'description',
        'content',
        'preview_image',
        'is_active',
        'is_premium',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    /**
     * Get the landings that use this template.
     */
    public function landings(): HasMany
    {
        return $this->hasMany(Landing::class);
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for free templates.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }
} 