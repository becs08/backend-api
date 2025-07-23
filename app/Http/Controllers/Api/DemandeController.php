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
        $validated = $request->validate([
            'message' => 'required|string',
            'prix_propose' => 'nullable|numeric|min:0',
        ]);

        $validated['demandeur_id'] = Auth::id();
        $validated['offre_id'] = $offre->id;

        $demande = Demande::create($validated);

        return response()->json([
            'message' => 'Demande créée avec succès',
            'demande' => $demande
        ], 201);
    }

    public function update(Request $request, Demande $demande)
    {
        // Vérifier que l'utilisateur peut répondre à cette demande
        if (Auth::id() !== $demande->offre->user_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|in:acceptee,refusee',
            'message_reponse' => 'nullable|string',
        ]);

        $validated['date_reponse'] = now();

        $demande->update($validated);

        return response()->json([
            'message' => 'Réponse enregistrée avec succès',
            'demande' => $demande
        ]);
    }

    public function mesDemandes(Request $request)
    {
        $demandes = Demande::where('demandeur_id', Auth::id())
            ->with([
                'offre:id,titre,localisation,user_id',
                'offre.user:id,name'
            ])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json($demandes);
    }

    public function demandesRecues(Request $request)
    {
        $demandes = Demande::whereHas('offre', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with([
                'demandeur:id,name',
                'offre:id,titre,localisation,user_id'
            ])
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json($demandes);
    }
}
