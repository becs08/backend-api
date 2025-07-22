<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    public function store(Request $request, Offre $offre)
    {
        // Vérifier que l'utilisateur ne demande pas sa propre offre
        if ($offre->user_id === Auth::id()) {
            return response()->json([
                'message' => 'Vous ne pouvez pas faire une demande sur votre propre offre.'
            ], 422);
        }

        // Vérifier qu'il n'y a pas déjà une demande en attente
        $demandeExistante = Demande::where('offre_id', $offre->id)
            ->where('demandeur_id', Auth::id())
            ->where('statut', 'en_attente')
            ->exists();

        if ($demandeExistante) {
            return response()->json([
                'message' => 'Vous avez déjà une demande en attente pour cette offre.'
            ], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'prix_propose' => 'nullable|numeric|min:0',
        ]);

        $validated['offre_id'] = $offre->id;
        $validated['demandeur_id'] = Auth::id();

        $demande = Demande::create($validated);
        $demande->load(['demandeur:id,name', 'offre:id,titre']);

        return response()->json([
            'message' => 'Demande envoyée avec succès',
            'demande' => $demande
        ], 201);
    }

    public function update(Request $request, Demande $demande)
    {
        // Seul l'offreur peut répondre à la demande
        if ($demande->offre->user_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|in:acceptee,refusee',
            'message_reponse' => 'nullable|string',
        ]);

        $validated['date_reponse'] = now();

        $demande->update($validated);
        $demande->load(['demandeur:id,name', 'offre:id,titre']);

        return response()->json([
            'message' => 'Réponse envoyée avec succès',
            'demande' => $demande
        ]);
    }

    public function mesDemandes(Request $request)
    {
        $demandes = Demande::where('demandeur_id', Auth::id())
            ->with(['offre:id,titre,user_id', 'offre.user:id,name'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return response()->json($demandes);
    }

    public function demandesRecues(Request $request)
    {
        $demandes = Demande::whereHas('offre', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['demandeur:id,name', 'offre:id,titre'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return response()->json($demandes);
    }
}
