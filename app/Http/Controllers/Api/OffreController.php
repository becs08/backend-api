<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $query = Offre::active()->with('user:id,name');

        // Filtres
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categorie') && $request->categorie !== 'all') {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        if ($request->filled('type_offre') && $request->type_offre !== 'all') {
            $query->where('type_offre', $request->type_offre);
        }

        $offres = $query->latest()
            ->paginate($request->get('per_page', 12))
            ->withQueryString();

        return response()->json($offres);
    }

    public function show(Offre $offre)
    {
        $offre->load('user:id,name');
        $offre->incrementerVues();

        $canEdit = Auth::check() && Auth::id() === $offre->user_id;

        // Offres liées
        $offresLiees = Offre::active()
            ->where('categorie', $offre->categorie)
            ->where('id', '!=', $offre->id)
            ->with('user:id,name')
            ->take(4)
            ->get();

        return response()->json([
            'offre' => $offre,
            'can_edit' => $canEdit,
            'offres_liees' => $offresLiees
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'required|string',
            'prix_min' => 'nullable|numeric|min:0',
            'prix_max' => 'nullable|numeric|min:0|gte:prix_min',
            'localisation' => 'required|string|max:255',
            'type_offre' => 'required|in:service,produit,formation',
            'date_expiration' => 'nullable|date|after:today',
        ]);

        $validated['user_id'] = Auth::id();

        $offre = Offre::create($validated);
        $offre->load('user:id,name');

        return response()->json([
            'message' => 'Offre créée avec succès',
            'offre' => $offre
        ], 201);
    }

    public function update(Request $request, Offre $offre)
    {
        if (Auth::id() !== $offre->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'required|string',
            'prix_min' => 'nullable|numeric|min:0',
            'prix_max' => 'nullable|numeric|min:0|gte:prix_min',
            'localisation' => 'required|string|max:255',
            'type_offre' => 'required|in:service,produit,formation',
            'date_expiration' => 'nullable|date|after:today',
            'statut' => 'nullable|in:active,suspendue',
        ]);

        $offre->update($validated);
        $offre->load('user:id,name');

        return response()->json([
            'message' => 'Offre mise à jour avec succès',
            'offre' => $offre
        ]);
    }

    public function destroy(Offre $offre)
    {
        if (Auth::id() !== $offre->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $offre->delete();

        return response()->json(['message' => 'Offre supprimée avec succès']);
    }

    public function mesOffres(Request $request)
    {
        $offres = Offre::where('user_id', Auth::id())
            ->withCount('demandes')
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json($offres);
    }

    public function categories()
    {
        $categories = [
            'informatique' => 'Informatique',
            'jardinage' => 'Jardinage',
            'menage' => 'Ménage',
            'bricolage' => 'Bricolage',
            'cours' => 'Cours particuliers',
            'transport' => 'Transport',
            'autre' => 'Autre'
        ];

        return response()->json($categories);
    }
}
