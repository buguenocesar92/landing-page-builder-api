<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ProductClick extends Model
{
    protected $fillable = [
        'landing_id',
        'product_name',
        'product_price',
        'product_category',
        'product_sku',
        'button_text',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
        'product_data',
    ];

    protected $casts = [
        'product_data' => 'array',
        'product_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the landing that this click belongs to.
     */
    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class);
    }

    /**
     * Scope for clicks in a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for clicks from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope for clicks from this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope for clicks from this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
    }

    /**
     * Scope for clicks by landing.
     */
    public function scopeByLanding($query, $landingId)
    {
        return $query->where('landing_id', $landingId);
    }

    /**
     * Scope for clicks by product name.
     */
    public function scopeByProduct($query, $productName)
    {
        return $query->where('product_name', $productName);
    }

    /**
     * Get popular products statistics.
     */
    public static function getPopularProducts($landingId = null, $limit = 10, $dateRange = null)
    {
        $query = static::selectRaw('
            product_name,
            product_category,
            product_price,
            COUNT(*) as clicks_count,
            COUNT(DISTINCT session_id) as unique_visitors,
            AVG(product_price) as avg_price
        ');

        if ($landingId) {
            $query->where('landing_id', $landingId);
        }

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        return $query->groupBy('product_name', 'product_category', 'product_price')
                    ->orderBy('clicks_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get clicks statistics by hour for charts.
     */
    public static function getClicksByHour($landingId = null, $date = null)
    {
        $query = static::selectRaw('
            HOUR(created_at) as hour,
            COUNT(*) as clicks_count
        ');

        if ($landingId) {
            $query->where('landing_id', $landingId);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        return $query->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
    }

    /**
     * Get total revenue potential (clicks * price).
     */
    public static function getRevenuePotential($landingId = null, $dateRange = null)
    {
        $query = static::selectRaw('
            SUM(product_price) as total_revenue_potential,
            AVG(product_price) as avg_product_price,
            COUNT(*) as total_clicks
        ');

        if ($landingId) {
            $query->where('landing_id', $landingId);
        }

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        return $query->first();
    }
} 