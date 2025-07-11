<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\LandingController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\ProductClickController;
use App\Http\Controllers\AuthController;

// Ruta de testing
Route::get('/test', function () {
    return response()->json(['message' => 'Landing Page Builder API funcionando correctamente']);
});

// ========================================
// AUTHENTICATION ROUTES (JWT) - BÁSICO
// ========================================
Route::post("/register", [AuthController::class, "register"])->name("register");
Route::post("/login", [AuthController::class,"login"])->name( "login");

// Rutas protegidas con JWT - Solo autenticación básica
Route::middleware("jwt.auth")->group(function(){
    // Auth endpoints básicos
    Route::get('who', [AuthController::class, 'who']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// ========================================
// TEMPLATES ROUTES
// ========================================
Route::prefix('templates')->group(function () {
    Route::get('/', [TemplateController::class, 'index']);
    Route::post('/', [TemplateController::class, 'store']);
    Route::get('/free', [TemplateController::class, 'free']);
    Route::get('/premium', [TemplateController::class, 'premium']);
    Route::get('/{template}', [TemplateController::class, 'show']);
    Route::put('/{template}', [TemplateController::class, 'update']);
    Route::delete('/{template}', [TemplateController::class, 'destroy']);
});

// ========================================
// LANDING PAGES ROUTES (PROTEGIDAS)
// ========================================
Route::middleware("jwt.auth")->group(function(){
    Route::prefix('landings')->group(function () {
        Route::get('/', [LandingController::class, 'index']);
        Route::post('/', [LandingController::class, 'store']);
        Route::get('/{landing}', [LandingController::class, 'show']);
        Route::put('/{landing}', [LandingController::class, 'update']);
        Route::delete('/{landing}', [LandingController::class, 'destroy']);
        
        // Rutas especiales
        Route::get('/{landing}/analytics', [LandingController::class, 'analytics']);
        Route::post('/{landing}/duplicate', [LandingController::class, 'duplicate']);
        Route::post('/{landing}/increment-views', [LandingController::class, 'incrementViews']);
    });
});

// Ruta pública para ver landing por slug
Route::get('/l/{slug}', [LandingController::class, 'getBySlug']);

// ========================================
// LEADS ROUTES
// ========================================
Route::prefix('leads')->group(function () {
    Route::get('/', [LeadController::class, 'index']);
    Route::get('/stats', [LeadController::class, 'stats']);
    Route::get('/export', [LeadController::class, 'export']);
    Route::get('/{lead}', [LeadController::class, 'show']);
    Route::put('/{lead}', [LeadController::class, 'update']);
    Route::delete('/{lead}', [LeadController::class, 'destroy']);
});

// Ruta pública para capturar leads (formulario público)
Route::post('/submit-lead', [LeadController::class, 'store']);

// ========================================
// PRODUCT CLICKS / ANALYTICS ROUTES
// ========================================

// Ruta pública para registrar clics en productos
Route::post('/track-product-click', [ProductClickController::class, 'track']);

// Rutas protegidas para ver estadísticas
Route::middleware("jwt.auth")->group(function(){
    Route::prefix('product-analytics')->group(function () {
        // Estadísticas por landing page
        Route::get('/landing/{landing}/stats', [ProductClickController::class, 'getStats']);
        
        // Estadísticas globales de productos
        Route::get('/global-stats', [ProductClickController::class, 'getGlobalStats']);
        
        // Detalles específicos de un producto
        Route::get('/landing/{landing}/product/{productName}', [ProductClickController::class, 'getProductDetails']);
    });
});

// ========================================
// DASHBOARD/STATS ROUTES
// ========================================
Route::middleware("jwt.auth")->group(function(){
    Route::get('/dashboard/stats', function() {
        // 🔒 SEGURIDAD: Usar usuario autenticado
        $userId = auth()->user()->id;
        
        $totalLandings = \App\Models\Landing::where('user_id', $userId)->count();
        $totalLeads = \App\Models\Lead::whereHas('landing', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
        $totalViews = \App\Models\Landing::where('user_id', $userId)->sum('views_count');
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_landings' => $totalLandings,
                'total_leads' => $totalLeads,
                'total_views' => $totalViews,
                'conversion_rate' => $totalViews > 0 ? round(($totalLeads / $totalViews) * 100, 2) : 0,
                'recent_activity' => [
                    'recent_landings' => \App\Models\Landing::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get(),
                    'recent_leads' => \App\Models\Lead::whereHas('landing', function($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->with('landing')
                        ->get()
                ]
            ]
        ]);
    });
});

// ========================================
// UTILITY ROUTES
// ========================================
Route::post('/utils/generate-slug', function(Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:255'
    ]);
    
    $baseSlug = \Illuminate\Support\Str::slug($validated['title']);
    $slug = $baseSlug;
    $counter = 1;
    
    // Verificar unicidad del slug
    while (\App\Models\Landing::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    return response()->json([
        'success' => true,
        'data' => ['slug' => $slug]
    ]);
});

Route::get('/utils/check-slug/{slug}', function($slug) {
    $exists = \App\Models\Landing::where('slug', $slug)->exists();
    
    return response()->json([
        'success' => true,
        'data' => [
            'available' => !$exists,
            'slug' => $slug
        ]
    ]);
}); 