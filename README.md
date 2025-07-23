# 🚀 Guide d'Installation - Plateforme MFPT

Ce projet est composé de **deux applications Laravel séparées** :
- **🖥️ Frontend** : Interface utilisateur (Laravel + Blade + Tailwind)
- **🔧 Backend** : API REST (Laravel + Base de données)

## 📋 **Prérequis**

Avant de commencer, assurez-vous d'avoir installé :

- **PHP 8.2+** ([Télécharger PHP](https://www.php.net/downloads.php))
- **Composer** ([Télécharger Composer](https://getcomposer.org/download/))
- **Node.js 18+** ([Télécharger Node.js](https://nodejs.org/))
- **Git** ([Télécharger Git](https://git-scm.com/downloads))

## 🔧 **Installation**

### **1. Cloner le projet**
```bash
git clone [URL_DU_REPO]
cd [NOM_DU_PROJET]
```

### **2. Installation du BACKEND (Port 8000)**

```bash
# Aller dans le dossier backend
cd backend-app  # ou le nom de votre dossier backend

# Installer les dépendances PHP
composer install

# Copier et configurer l'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données SQLite
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate

# Insérer les données de test
php artisan db:seed --class=TestDataSeeder
```

**Configuration du fichier `.env` du BACKEND :**
```env
APP_NAME="MFPT Backend API"
APP_ENV=local
APP_KEY=[GÉNÉRÉ_AUTOMATIQUEMENT]
APP_DEBUG=true
APP_URL=http://localhost:8000

# Configuration Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,127.0.0.1:3000,::1
FRONTEND_URL=http://localhost:3000

# Base de données
DB_CONNECTION=sqlite

# Autres configurations...
```

### **3. Installation du FRONTEND (Port 3000)**

```bash
# Aller dans le dossier frontend
cd ../frontend-app  # ou le nom de votre dossier frontend

# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js
npm install

# Copier et configurer l'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer la base de données SQLite (pour les sessions)
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate
```

**Configuration du fichier `.env` du FRONTEND :**
```env
APP_NAME="MFPT Frontend"
APP_ENV=local
APP_KEY=[GÉNÉRÉ_AUTOMATIQUEMENT]
APP_DEBUG=true
APP_URL=http://localhost:3000

# Configuration pour communiquer avec l'API Backend
API_BASE_URL=http://localhost:8000/api
API_TIMEOUT=30

VITE_API_BASE_URL="${API_BASE_URL}"

# Base de données
DB_CONNECTION=sqlite

# Autres configurations...
```

## 🚀 **Démarrage de l'application**

### **Option 1 : Démarrage automatique (RECOMMANDÉ)**

**Terminal 1 - Backend :**
```bash
cd backend-app
php artisan serve --port=8000
```

**Terminal 2 - Frontend :**
```bash
cd frontend-app
composer run dev
```

### **Option 2 : Démarrage manuel**

**Terminal 1 - Backend :**
```bash
cd backend-app
php artisan serve --port=8000
```

**Terminal 2 - Frontend (serveur) :**
```bash
cd frontend-app
php artisan serve --port=3000
```

**Terminal 3 - Frontend (assets) :**
```bash
cd frontend-app
npm run dev
```

## 🌐 **Accès à l'application**

Une fois les serveurs démarrés :

- **🖥️ Interface utilisateur** : http://localhost:3000
- **🔧 API Backend** : http://localhost:8000/api
- **📊 Test API** : http://localhost:3000/debug/offres-performance

## 👤 **Comptes de test**

Utilisez ces comptes pour tester l'application (mot de passe : `password`) :

### **Offreurs (proposent des services) :**
- **Email :** `marie@example.com`
- **Email :** `pierre@example.com`

### **Demandeurs (recherchent des services) :**
- **Email :** `sophie@example.com`
- **Email :** `jean@example.com`

## 🧪 **Fonctionnalités à tester**

1. **📝 Inscription/Connexion**
2. **📋 Parcourir les offres** (page d'accueil)
3. **➕ Créer une offre** (en tant qu'offreur)
4. **💬 Faire une demande** (en tant que demandeur)
5. **✅ Accepter/Refuser des demandes** (en tant qu'offreur)
6. **👀 Suivre mes demandes** (en tant que demandeur)

## 📁 **Structure du projet**

```
projet/
├── backend-app/                 # API Laravel
│   ├── app/Http/Controllers/Api/
│   ├── app/Models/
│   ├── database/
│   └── routes/api.php
├── frontend-app/                # Interface Laravel
│   ├── app/Http/Controllers/
│   ├── app/Services/ApiClient.php
│   ├── resources/views/
│   └── routes/web.php
└── README.md
```

## 🛠️ **Commandes utiles**

### **Backend :**
```bash
# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Voir les logs en temps réel
php artisan pail

# Lister les routes API
php artisan route:list
```

### **Frontend :**
```bash
# Vider le cache
php artisan config:clear
php artisan cache:clear

# Recompiler les assets
npm run build
```

## 🚨 **Problèmes courants**

### **Port déjà utilisé**
```bash
# Si le port 3000 est occupé
php artisan serve --port=3001

# Si le port 8000 est occupé  
php artisan serve --port=8001
# (N'oubliez pas de modifier API_BASE_URL dans le .env frontend)
```

### **Erreur de connexion API**
1. Vérifiez que le backend tourne sur le port 8000
2. Vérifiez que `API_BASE_URL=http://localhost:8000/api` dans le .env frontend
3. Testez l'API directement : http://localhost:8000/api/offres

### **Erreur de base de données**
```bash
# Recréer la base de données
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate --seed
```

## 📞 **Support**

Si vous rencontrez des problèmes :
1. Vérifiez que tous les prérequis sont installés
2. Vérifiez que les deux serveurs tournent
3. Consultez les logs : `php artisan pail` ou `storage/logs/laravel.log`

## 🎯 **Architecture**

- **Frontend** : Envoie des requêtes HTTP à l'API via `ApiClient`
- **Backend** : Fournit une API REST avec authentification Sanctum
- **Base de données** : SQLite (facile à partager)
- **Authentification** : Sessions côté frontend, tokens Sanctum côté API