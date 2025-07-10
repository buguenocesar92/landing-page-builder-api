<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Template::query();

        // Filtro por estado activo
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filtro por templates premium/gratuitos
        if ($request->has('premium')) {
            $query->where('is_premium', $request->boolean('premium'));
        }

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $templates = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'required|array',
                'preview_image' => 'nullable|string|url',
                'is_active' => 'boolean',
                'is_premium' => 'boolean',
            ]);

            $template = Template::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Template creado exitosamente',
                'data' => $template
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
     * Display the specified template.
     */
    public function show(Template $template): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $template
        ]);
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, Template $template): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'content' => 'sometimes|array',
                'preview_image' => 'nullable|string|url',
                'is_active' => 'boolean',
                'is_premium' => 'boolean',
            ]);

            $template->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Template actualizado exitosamente',
                'data' => $template->fresh()
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
     * Remove the specified template.
     */
    public function destroy(Template $template): JsonResponse
    {
        // Verificar si hay landing pages usando este template
        if ($template->landings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el template porque está siendo usado por landing pages'
            ], 409);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template eliminado exitosamente'
        ]);
    }

    /**
     * Get only free templates.
     */
    public function free(): JsonResponse
    {
        $templates = Template::active()->free()->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Get only premium templates.
     */
    public function premium(): JsonResponse
    {
        $templates = Template::active()->where('is_premium', true)->get();

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }
}
