<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    /**
     * Display a listing of leads.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::with('landing');

        // Filtro por landing page
        if ($request->filled('landing_id')) {
            $query->where('landing_id', $request->landing_id);
        }

        // Filtro por usuario a través de landing
        if ($request->filled('user_id')) {
            $query->whereHas('landing', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filtro por fecha
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }

    /**
     * Store a newly created lead (desde formulario público).
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'landing_id' => 'required|exists:landings,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'message' => 'nullable|string|max:1000',
                'extra_data' => 'nullable|array',
            ]);

            // Verificar que la landing page esté activa
            $landing = Landing::findOrFail($validated['landing_id']);
            if (!$landing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'La landing page no está disponible'
                ], 400);
            }

            // Agregar datos del request (IP, User Agent)
            $validated['ip_address'] = $request->ip();
            $validated['user_agent'] = $request->userAgent();

            $lead = Lead::create($validated);

            // Incrementar contador de leads en la landing page
            $landing->incrementLeads();

            $lead->load('landing');

            return response()->json([
                'success' => true,
                'message' => '¡Gracias! Tu información ha sido registrada exitosamente',
                'data' => $lead
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead): JsonResponse
    {
        $lead->load('landing');

        return response()->json([
            'success' => true,
            'data' => $lead
        ]);
    }

    /**
     * Update the specified lead (solo para admin).
     */
    public function update(Request $request, Lead $lead): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'phone' => 'nullable|string|max:20',
                'message' => 'nullable|string|max:1000',
                'extra_data' => 'nullable|array',
            ]);

            $lead->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Lead actualizado exitosamente',
                'data' => $lead
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Lead $lead): JsonResponse
    {
        // Decrementar contador en la landing page
        $lead->landing->decrement('leads_count');
        
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead eliminado exitosamente'
        ]);
    }

    /**
     * Export leads to CSV.
     */
    public function export(Request $request): JsonResponse
    {
        $query = Lead::with('landing');

        // Aplicar filtros similares al index
        if ($request->filled('landing_id')) {
            $query->where('landing_id', $request->landing_id);
        }

        if ($request->filled('user_id')) {
            $query->whereHas('landing', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        // Formatear datos para CSV
        $csvData = $leads->map(function($lead) {
            return [
                'fecha' => $lead->created_at->format('Y-m-d H:i:s'),
                'landing_page' => $lead->landing->title,
                'nombre' => $lead->name,
                'email' => $lead->email,
                'telefono' => $lead->phone ?? '',
                'mensaje' => $lead->message ?? '',
                'ip_address' => $lead->ip_address ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $csvData,
            'filename' => 'leads_' . now()->format('Y-m-d_H-i-s') . '.csv'
        ]);
    }

    /**
     * Get leads statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $query = Lead::query();

        // Filtro por usuario a través de landing
        if ($request->filled('user_id')) {
            $query->whereHas('landing', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Filtro por landing page
        if ($request->filled('landing_id')) {
            $query->where('landing_id', $request->landing_id);
        }

        $stats = [
            'total_leads' => $query->count(),
            'leads_today' => $query->whereDate('created_at', today())->count(),
            'leads_this_week' => $query->whereBetween('created_at', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])->count(),
            'leads_this_month' => $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'leads_by_day' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_landing_pages' => $query->selectRaw('landing_id, COUNT(*) as leads_count')
                ->with('landing:id,title')
                ->groupBy('landing_id')
                ->orderByDesc('leads_count')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
