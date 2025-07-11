<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductClick;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProductClickController extends Controller
{
    /**
     * Registrar un clic en producto (endpoint público).
     */
    public function track(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'landing_slug' => 'required|string',
                'product_name' => 'required|string|max:255',
                'product_price' => 'nullable|numeric|min:0',
                'product_category' => 'nullable|string|max:100',
                'product_sku' => 'nullable|string|max:100',
                'button_text' => 'nullable|string|max:50',
                'session_id' => 'nullable|string|max:100',
                'product_data' => 'nullable|array',
            ]);

            // Encontrar la landing page por slug
            $landing = Landing::where('slug', $validated['landing_slug'])->first();
            
            if (!$landing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Landing page no encontrada'
                ], 404);
            }

            // Crear el registro de clic
            $click = ProductClick::create([
                'landing_id' => $landing->id,
                'product_name' => $validated['product_name'],
                'product_price' => $validated['product_price'] ?? null,
                'product_category' => $validated['product_category'] ?? null,
                'product_sku' => $validated['product_sku'] ?? null,
                'button_text' => $validated['button_text'] ?? 'Comprar',
                'session_id' => $validated['session_id'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'product_data' => $validated['product_data'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Clic registrado exitosamente',
                'data' => [
                    'click_id' => $click->id,
                    'product_name' => $click->product_name,
                    'timestamp' => $click->created_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de clics para una landing page.
     */
    public function getStats(Request $request, $landingId): JsonResponse
    {
        $landing = Landing::find($landingId);
        
        if (!$landing) {
            return response()->json([
                'success' => false,
                'message' => 'Landing page no encontrada'
            ], 404);
        }

        // 🔒 SEGURIDAD: Verificar que el usuario sea el propietario
        if ($landing->user_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver los analytics de esta landing page'
            ], 403);
        }

        // Parámetros de filtro
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $limit = $request->get('limit', 10);

        $dateRange = [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ];

        // Productos más populares
        $popularProducts = ProductClick::getPopularProducts($landingId, $limit, $dateRange);

        // Estadísticas generales
        $totalClicks = ProductClick::byLanding($landingId)
            ->dateRange($dateRange[0], $dateRange[1])
            ->count();

        $uniqueVisitors = ProductClick::byLanding($landingId)
            ->dateRange($dateRange[0], $dateRange[1])
            ->distinct('session_id')
            ->count('session_id');

        // Potencial de ingresos
        $revenueStats = ProductClick::getRevenuePotential($landingId, $dateRange);

        // Clics por hora (último día)
        $clicksByHour = ProductClick::getClicksByHour($landingId, Carbon::today());

        // Clics por categoría
        $clicksByCategory = ProductClick::byLanding($landingId)
            ->dateRange($dateRange[0], $dateRange[1])
            ->selectRaw('product_category, COUNT(*) as clicks_count')
            ->whereNotNull('product_category')
            ->groupBy('product_category')
            ->orderBy('clicks_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'landing' => [
                    'id' => $landing->id,
                    'title' => $landing->title,
                    'slug' => $landing->slug
                ],
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ],
                'summary' => [
                    'total_clicks' => $totalClicks,
                    'unique_visitors' => $uniqueVisitors,
                    'avg_clicks_per_visitor' => $uniqueVisitors > 0 ? round($totalClicks / $uniqueVisitors, 2) : 0,
                    'total_revenue_potential' => $revenueStats->total_revenue_potential ?? 0,
                    'avg_product_price' => $revenueStats->avg_product_price ?? 0
                ],
                'popular_products' => $popularProducts,
                'clicks_by_hour' => $clicksByHour,
                'clicks_by_category' => $clicksByCategory
            ]
        ]);
    }

    /**
     * Obtener estadísticas globales de todos los productos del usuario autenticado.
     */
    public function getGlobalStats(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $limit = $request->get('limit', 20);

        $dateRange = [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ];

        // 🔒 SEGURIDAD: Obtener solo landing pages del usuario autenticado
        $userLandingIds = Landing::where('user_id', auth()->user()->id)->pluck('id');

        // Productos más populares del usuario
        $globalPopular = ProductClick::whereIn('landing_id', $userLandingIds)
            ->dateRange($dateRange[0], $dateRange[1])
            ->selectRaw('
                product_name,
                product_category,
                product_price,
                COUNT(*) as clicks_count,
                COUNT(DISTINCT session_id) as unique_visitors,
                AVG(product_price) as avg_price
            ')
            ->groupBy('product_name', 'product_category', 'product_price')
            ->orderBy('clicks_count', 'desc')
            ->limit($limit)
            ->get();

        // Top landing pages por clics en productos del usuario
        $topLandings = ProductClick::selectRaw('
            landing_id,
            COUNT(*) as total_clicks,
            COUNT(DISTINCT session_id) as unique_visitors,
            SUM(product_price) as revenue_potential
        ')
        ->with('landing:id,title,slug')
        ->whereIn('landing_id', $userLandingIds)
        ->dateRange($dateRange[0], $dateRange[1])
        ->groupBy('landing_id')
        ->orderBy('total_clicks', 'desc')
        ->limit(10)
        ->get();

        // Estadísticas por día del usuario
        $dailyStats = ProductClick::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as clicks_count,
            COUNT(DISTINCT session_id) as unique_visitors,
            SUM(product_price) as revenue_potential
        ')
        ->whereIn('landing_id', $userLandingIds)
        ->dateRange($dateRange[0], $dateRange[1])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ],
                'global_popular_products' => $globalPopular,
                'top_landing_pages' => $topLandings,
                'daily_statistics' => $dailyStats
            ]
        ]);
    }

    /**
     * Obtener detalles específicos de un producto.
     */
    public function getProductDetails(Request $request, $landingId, $productName): JsonResponse
    {
        $landing = Landing::find($landingId);
        
        if (!$landing) {
            return response()->json([
                'success' => false,
                'message' => 'Landing page no encontrada'
            ], 404);
        }

        // 🔒 SEGURIDAD: Verificar que el usuario sea el propietario
        if ($landing->user_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver los detalles de productos de esta landing page'
            ], 403);
        }

        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $dateRange = [
            Carbon::parse($dateFrom)->startOfDay(),
            Carbon::parse($dateTo)->endOfDay()
        ];

        // Estadísticas específicas del producto
        $productStats = ProductClick::byLanding($landingId)
            ->byProduct($productName)
            ->dateRange($dateRange[0], $dateRange[1])
            ->selectRaw('
                COUNT(*) as total_clicks,
                COUNT(DISTINCT session_id) as unique_visitors,
                COUNT(DISTINCT DATE(created_at)) as active_days,
                AVG(product_price) as avg_price,
                MIN(created_at) as first_click,
                MAX(created_at) as last_click
            ')
            ->first();

        // Clics por día para el producto específico
        $dailyClicks = ProductClick::byLanding($landingId)
            ->byProduct($productName)
            ->dateRange($dateRange[0], $dateRange[1])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'product_name' => $productName,
                'landing' => [
                    'id' => $landing->id,
                    'title' => $landing->title,
                    'slug' => $landing->slug
                ],
                'stats' => $productStats,
                'daily_clicks' => $dailyClicks
            ]
        ]);
    }
} 