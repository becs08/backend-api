<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validation flexible selon les champs envoyés
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'user_type' => 'required|in:demandeur,offreur',
            ];

            // Si le frontend envoie confirmPassword, on l'utilise
            if ($request->has('confirmPassword')) {
                $rules['confirmPassword'] = 'required|string|min:8|same:password';
            }
            // Sinon si il envoie password_confirmation, on l'utilise
            elseif ($request->has('password_confirmation')) {
                $rules['password_confirmation'] = 'required|string|min:8|same:password';
            }

            $validated = $request->validate($rules);

            $validated['password'] = Hash::make($validated['password']);
            
            // Retirer les champs de confirmation avant de créer l'utilisateur
            unset($validated['password_confirmation']);
            unset($validated['confirmPassword']);

            $user = User::create($validated);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user->fresh(),
                'token' => $token
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'inscription', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'inscription',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Les informations de connexion sont incorrectes.'],
            ]);
        }

        $user = Auth::user();
        $user->mettreAJourDerniereConnexion();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ]);
    }

    public function dashboardStats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'mes_offres' => $user->offres()->count(),
            'offres_actives' => $user->offres()->where('statut', 'active')->count(),
            'mes_demandes' => $user->demandes()->count(),
            'demandes_recues' => \App\Models\Demande::whereHas('offre', fn($q) => $q->where('user_id', $user->id))->count(),
        ];

        // Demandes récentes reçues
        $demandesRecentes = \App\Models\Demande::whereHas('offre', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['demandeur:id,name', 'offre:id,titre'])
            ->latest()
            ->take(5)
            ->get();

        // Mes dernières offres
        $mesOffresRecentes = $user->offres()
            ->withCount('demandes')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'demandes_recentes' => $demandesRecentes,
            'mes_offres_recentes' => $mesOffresRecentes
        ]);
    }
}
