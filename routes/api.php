<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\LandingController;
use App\Http\Controllers\API\LeadController;
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
// LANDING PAGES ROUTES
// ========================================
Route::prefix('landings')->group(function () {
    Route::get('/', [LandingController::class, 'index']);
    Route::post('/', [LandingController::class, 'store']);
    Route::get('/{landing}', [LandingController::class, 'show']);
    Route::put('/{landing}', [LandingController::class, 'update']);
    Route::delete('/{landing}', [LandingController::class, 'destroy']);
    
    // Rutas especiales
    Route::get('/{landing}/analytics', [LandingController::class, 'analytics']);
    Route::post('/{landing}/duplicate', [LandingController::class, 'duplicate']);
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
// DASHBOARD/STATS ROUTES
// ========================================
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', function (Request $request) {
        $userId = $request->get('user_id');
        
        $stats = [
            'total_landings' => \App\Models\Landing::when($userId, function($q) use ($userId) {
                return $q->where('user_id', $userId);
            })->count(),
            
            'total_leads' => \App\Models\Lead::when($userId, function($q) use ($userId) {
                return $q->whereHas('landing', function($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                });
            })->count(),
            
            'total_views' => \App\Models\Landing::when($userId, function($q) use ($userId) {
                return $q->where('user_id', $userId);
            })->sum('views_count'),
            
            'active_landings' => \App\Models\Landing::when($userId, function($q) use ($userId) {
                return $q->where('user_id', $userId);
            })->where('is_active', true)->count(),
            
            'conversion_rate' => (function() use ($userId) {
                $totalViews = \App\Models\Landing::when($userId, function($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })->sum('views_count');
                
                $totalLeads = \App\Models\Lead::when($userId, function($q) use ($userId) {
                    return $q->whereHas('landing', function($q2) use ($userId) {
                        $q2->where('user_id', $userId);
                    });
                })->count();
                
                return $totalViews > 0 ? round(($totalLeads / $totalViews) * 100, 2) : 0;
            })(),
            
            'recent_activity' => [
                'recent_leads' => \App\Models\Lead::with('landing:id,title')
                    ->when($userId, function($q) use ($userId) {
                        return $q->whereHas('landing', function($q2) use ($userId) {
                            $q2->where('user_id', $userId);
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                    
                'recent_landings' => \App\Models\Landing::when($userId, function($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    });
});

// ========================================
// UTILIDADES
// ========================================
Route::prefix('utils')->group(function () {
    // Verificar disponibilidad de slug
    Route::get('/check-slug/{slug}', function (string $slug) {
        $exists = \App\Models\Landing::where('slug', $slug)->exists();
        
        return response()->json([
            'success' => true,
            'available' => !$exists,
            'suggested' => !$exists ? $slug : $slug . '-' . \Illuminate\Support\Str::random(6)
        ]);
    });
    
    // Generar slug desde título
    Route::post('/generate-slug', function (Request $request) {
        $request->validate(['title' => 'required|string']);
        
        $baseSlug = \Illuminate\Support\Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;
        
        while (\App\Models\Landing::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return response()->json([
            'success' => true,
            'slug' => $slug
        ]);
    });
}); 