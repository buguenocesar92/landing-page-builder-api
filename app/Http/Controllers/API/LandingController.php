<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    /**
     * Display a listing of landings for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        // 🔒 SEGURIDAD: Solo mostrar landing pages del usuario autenticado
        $query = Landing::with(['template', 'user'])
            ->where('user_id', auth()->user()->id);

        // Filtro por estado activo
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Búsqueda por título
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $landings = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $landings
        ]);
    }

    /**
     * Store a newly created landing.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'template_id' => 'required|exists:templates,id',
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|unique:landings,slug',
                'description' => 'nullable|string',
                'content' => 'required|array',
                'custom_domain' => 'nullable|string|url',
                'is_active' => 'boolean',
            ]);

            // 🔒 SEGURIDAD: Asignar automáticamente el usuario autenticado
            $validated['user_id'] = auth()->user()->id;

            // Auto-generar slug si no se proporciona
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
            }

            // Verificar que el template existe y está activo
            $template = Template::findOrFail($validated['template_id']);
            if (!$template->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'El template seleccionado no está disponible'
                ], 400);
            }

            $landing = Landing::create($validated);
            $landing->load(['template', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Landing page creada exitosamente',
                'data' => $landing
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
     * Display the specified landing.
     */
    public function show(Landing $landing): JsonResponse
    {
        // 🔒 SEGURIDAD: Verificar que el usuario sea el propietario
        if ($landing->user_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver esta landing page'
            ], 403);
        }

        $landing->load(['template', 'user', 'leads']);

        return response()->json([
            'success' => true,
            'data' => $landing
        ]);
    }

    /**
     * Update the specified landing.
     */
    public function update(Request $request, Landing $landing): JsonResponse
    {
        // 🔒 SEGURIDAD: Verificar que el usuario sea el propietario
        if ($landing->user_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para editar esta landing page'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'template_id' => 'sometimes|exists:templates,id',
                'title' => 'sometimes|string|max:255',
                'slug' => 'sometimes|string|unique:landings,slug,' . $landing->id,
                'description' => 'nullable|string',
                'content' => 'sometimes|array',
                'custom_domain' => 'nullable|string|url',
                'is_active' => 'boolean',
            ]);

            // Si se cambia el template, verificar que esté activo
            if (isset($validated['template_id'])) {
                $template = Template::findOrFail($validated['template_id']);
                if (!$template->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El template seleccionado no está disponible'
                    ], 400);
                }
            }

            $landing->update($validated);
            $landing->load(['template', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Landing page actualizada exitosamente',
                'data' => $landing
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
     * Remove the specified landing.
     */
    public function destroy(Landing $landing): JsonResponse
    {
        // 🔒 SEGURIDAD: Verificar que el usuario sea el propietario
        if ($landing->user_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar esta landing page'
            ], 403);
        }

        $landing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Landing page eliminada exitosamente'
        ]);
    }

    /**
     * Get landing by slug for public view.
     */
    public function getBySlug(string $slug): JsonResponse
    {
        $landing = Landing::where('slug', $slug)
            ->where('is_active', true)
            ->with(['template', 'user'])
            ->first();

        if (!$landing) {
            return response()->json([
                'success' => false,
                'message' => 'Landing page no encontrada'
            ], 404);
        }

        // Incrementar contador de visitas
        $landing->incrementViews();

        // Asegurar que el contenido tenga campos de formulario básicos
        $content = $landing->content;
        if (!isset($content['form']) || !isset($content['form']['fields'])) {
            $content['form'] = [
                'title' => '¡Contáctanos!',
                'subtitle' => 'Déjanos tus datos y te contactaremos pronto',
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'label' => 'Nombre completo',
                        'required' => true,
                        'icon' => 'user'
                    ],
                    [
                        'name' => 'email',
                        'type' => 'email',
                        'label' => 'Email',
                        'required' => true,
                        'icon' => 'mail'
                    ],
                    [
                        'name' => 'phone',
                        'type' => 'tel',
                        'label' => 'Teléfono',
                        'required' => false,
                        'icon' => 'phone'
                    ],
                    [
                        'name' => 'message',
                        'type' => 'textarea',
                        'label' => 'Mensaje',
                        'required' => false,
                        'icon' => 'message-square'
                    ]
                ],
                'cta_text' => 'Enviar',
                'privacy_text' => 'Al enviar este formulario, aceptas nuestros términos y condiciones.'
            ];
            
            // Actualizar el landing con el formulario básico
            $landing->update(['content' => $content]);
        }

        return response()->json([
            'success' => true,
            'data' => $landing
        ]);
    }

    /**
     * Increment views count for landing page (public endpoint).
     */
    public function incrementViews(Landing $landing): JsonResponse
    {
        // Solo incrementar si la landing está activa
        if (!$landing->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'La landing page no está disponible'
            ], 400);
        }

        $landing->incrementViews();

        return response()->json([
            'success' => true,
            'message' => 'Vista registrada',
            'data' => [
                'views_count' => $landing->fresh()->views_count
            ]
        ]);
    }

    /**
     * Get analytics for a landing page.
     */
    public function analytics(Landing $landing): JsonResponse
    {
        $analytics = [
            'total_views' => $landing->views_count,
            'total_leads' => $landing->leads_count,
            'conversion_rate' => $landing->views_count > 0 
                ? round(($landing->leads_count / $landing->views_count) * 100, 2) 
                : 0,
            'recent_leads' => $landing->leads()
                ->select(['name', 'email', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'leads_by_day' => $landing->leads()
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Duplicate a landing page.
     */
    public function duplicate(Landing $landing): JsonResponse
    {
        $newLanding = $landing->replicate();
        $newLanding->title = $landing->title . ' (Copia)';
        $newLanding->slug = Str::slug($newLanding->title) . '-' . Str::random(6);
        $newLanding->views_count = 0;
        $newLanding->leads_count = 0;
        $newLanding->save();

        $newLanding->load(['template', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Landing page duplicada exitosamente',
            'data' => $newLanding
        ], 201);
    }
}
