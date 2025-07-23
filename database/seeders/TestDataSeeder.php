<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Offre;
use App\Models\Demande;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs de test
        $offreur1 = User::create([
            'name' => 'Marie Dubois',
            'email' => 'marie@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'offreur',
            'phone' => '06 12 34 56 78',
            'address' => '15 rue de la Paix',
            'city' => 'Paris',
            'postal_code' => '75001',
        ]);

        $offreur2 = User::create([
            'name' => 'Pierre Martin',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'offreur',
            'phone' => '06 98 76 54 32',
            'address' => '8 avenue des Champs',
            'city' => 'Lyon',
            'postal_code' => '69001',
        ]);

        $demandeur1 = User::create([
            'name' => 'Sophie Bernard',
            'email' => 'sophie@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'demandeur',
            'phone' => '06 11 22 33 44',
            'address' => '3 place du Marché',
            'city' => 'Marseille',
            'postal_code' => '13001',
        ]);

        $demandeur2 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'demandeur',
            'phone' => '06 55 66 77 88',
            'address' => '12 boulevard Victor Hugo',
            'city' => 'Toulouse',
            'postal_code' => '31000',
        ]);

        // Créer des offres de test
        $offre1 = Offre::create([
            'titre' => 'Cours de piano pour débutants',
            'description' => "Professeur de piano expérimenté propose des cours particuliers pour débutants et niveau intermédiaire.\n\nMéthode pédagogique adaptée à chaque élève, possibilité de cours à domicile ou en studio.\n\nPremier cours d'essai gratuit !",
            'categorie' => 'cours',
            'prix_min' => 25.00,
            'prix_max' => 40.00,
            'localisation' => 'Paris 15ème',
            'type_offre' => 'service',
            'statut' => 'active',
            'user_id' => $offreur1->id,
            'vues' => 45,
        ]);

        $offre2 = Offre::create([
            'titre' => 'Réparation ordinateurs et smartphones',
            'description' => "Service de réparation professionnel pour tous vos appareils électroniques :\n\n• Ordinateurs portables et fixes\n• Smartphones et tablettes\n• Diagnostics gratuits\n• Intervention rapide\n\nPlus de 10 ans d'expérience, devis gratuit.",
            'categorie' => 'informatique',
            'prix_min' => 30.00,
            'prix_max' => 150.00,
            'localisation' => 'Lyon Centre',
            'type_offre' => 'service',
            'statut' => 'active',
            'user_id' => $offreur2->id,
            'vues' => 73,
        ]);

        $offre3 = Offre::create([
            'titre' => 'Jardinage et entretien espaces verts',
            'description' => "Services de jardinage pour particuliers :\n\n✓ Tonte de pelouse\n✓ Taille de haies et arbustes\n✓ Plantation et arrosage\n✓ Désherbage\n✓ Entretien général\n\nTarifs compétitifs, intervention ponctuelle ou régulière possible.",
            'categorie' => 'jardinage',
            'prix_min' => 20.00,
            'prix_max' => 50.00,
            'localisation' => 'Paris et banlieue',
            'type_offre' => 'service',
            'statut' => 'active',
            'user_id' => $offreur1->id,
            'vues' => 28,
        ]);

        $offre4 = Offre::create([
            'titre' => 'Formation développement web',
            'description' => "Formation complète au développement web moderne :\n\n📚 HTML5, CSS3, JavaScript\n📚 PHP, Laravel\n📚 Base de données MySQL\n📚 Projet pratique inclus\n\nFormation sur mesure, rythme adapté à vos disponibilités.",
            'categorie' => 'informatique',
            'prix_min' => 400.00,
            'prix_max' => 800.00,
            'localisation' => 'Lyon ou en ligne',
            'type_offre' => 'formation',
            'statut' => 'active',
            'user_id' => $offreur2->id,
            'vues' => 92,
        ]);

        $offre5 = Offre::create([
            'titre' => 'Ménage et repassage à domicile',
            'description' => "Service de ménage professionnel à domicile :\n\n🏠 Nettoyage complet des pièces\n👕 Repassage du linge\n🧽 Produits fournis\n⏰ Horaires flexibles\n\nPersonne de confiance, références disponibles.",
            'categorie' => 'menage',
            'prix_min' => 15.00,
            'prix_max' => 25.00,
            'localisation' => 'Paris 11ème, 12ème, 20ème',
            'type_offre' => 'service',
            'statut' => 'active',
            'user_id' => $offreur1->id,
            'vues' => 67,
        ]);

        // Créer quelques demandes de test
        Demande::create([
            'message' => "Bonjour,\n\nJe suis intéressée par vos cours de piano. Je suis débutante complète et j'aimerais commencer par des bases solides.\n\nSeriez-vous disponible le samedi matin ? Merci !",
            'prix_propose' => 30.00,
            'statut' => 'en_attente',
            'offre_id' => $offre1->id,
            'demandeur_id' => $demandeur1->id,
        ]);

        Demande::create([
            'message' => "Bonjour,\n\nMon ordinateur portable ne démarre plus depuis hier. Écran noir complet.\n\nPourriez-vous intervenir rapidement ? Je travaille dessus tous les jours.\n\nMerci d'avance.",
            'prix_propose' => null,
            'statut' => 'acceptee',
            'offre_id' => $offre2->id,
            'demandeur_id' => $demandeur2->id,
            'date_reponse' => now(),
            'message_reponse' => "Bonjour,\n\nJe peux intervenir dès demain matin. Cela ressemble à un problème d'alimentation ou de carte mère.\n\nJe vous confirme l'horaire par téléphone.\n\nCordialement",
        ]);

        Demande::create([
            'message' => "Salut,\n\nJ'ai un petit jardin qui a besoin d'un bon nettoyage de printemps. Tonte, désherbage et taille de quelques arbustes.\n\nQuand seriez-vous disponible ?",
            'prix_propose' => 45.00,
            'statut' => 'refusee',
            'offre_id' => $offre3->id,
            'demandeur_id' => $demandeur1->id,
            'date_reponse' => now(),
            'message_reponse' => "Bonjour,\n\nMerci pour votre demande. Malheureusement je suis complet pour les 3 prochaines semaines.\n\nJe vous recommande de contacter un collègue.",
        ]);

        Demande::create([
            'message' => "Bonjour,\n\nJe souhaite me reconvertir dans le développement web. Votre formation m'intéresse beaucoup.\n\nPourriez-vous me donner plus de détails sur le contenu et la durée ?\n\nMerci !",
            'prix_propose' => 600.00,
            'statut' => 'en_attente',
            'offre_id' => $offre4->id,
            'demandeur_id' => $demandeur2->id,
        ]);

        echo "✅ Données de test créées avec succès !\n";
        echo "👤 Utilisateurs créés :\n";
        echo "   - marie@example.com (offreur)\n";
        echo "   - pierre@example.com (offreur)\n";
        echo "   - sophie@example.com (demandeur)\n";
        echo "   - jean@example.com (demandeur)\n";
        echo "📋 5 offres créées\n";
        echo "💬 4 demandes créées\n";
        echo "🔑 Mot de passe pour tous : password\n";
    }
}
