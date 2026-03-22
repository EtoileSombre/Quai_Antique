# Quai Antique

Application web pour le restaurant **Quai Antique**, restaurant savoyard situé à Chambéry.  
Projet réalisé dans le cadre de la formation **DWWM**.

---

## Fonctionnalités

- **Vitrine** : présentation du restaurant, galerie photos
- **Carte** : catégories, plats, menus avec descriptions et prix
- **Réservation** : système de réservation en ligne avec contrôle de capacité en temps réel (AJAX)
- **Authentification** : inscription client, connexion client/admin
- **Espace client** : gestion du profil, allergies, historique de réservations
- **Back-office admin** : gestion des horaires, capacité, carte, galerie, réservations

---

## Stack technique

| Catégorie | Technologies |
|-----------|-------------|
| Frontend | HTML5, Tailwind CSS, JavaScript |
| Backend | PHP 8.3, architecture MVC |
| Base de données | MySQL 8.0 |
| Accès aux données | PDO |
| Environnement | Docker Desktop, Docker Compose |
| Versioning | Git, GitHub |
| Gestion de projet | Trello |

---

## Installation

### Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Node.js](https://nodejs.org/) (pour Tailwind CSS)
- [Git](https://git-scm.com/)

### Lancer le projet

```bash
# Cloner le dépôt
git clone https://github.com/VOTRE_USER/quai-antique.git
cd quai-antique

# Copier le fichier d'environnement
cp .env.example .env

# Installer les dépendances front
npm install

# Compiler le CSS Tailwind
npm run build:css

# Lancer les conteneurs Docker
docker compose up -d
```

### Accès

| Service | URL |
|---------|-----|
| Application | http://localhost:8082 |
| phpMyAdmin | http://localhost:8092 |

### Identifiants

Les identifiants de la base de données et du compte admin sont définis dans le fichier `.env`.  
Consultez `.env.example` pour voir les variables nécessaires.

---

## Structure du projet

```
quai-antique/
├── app/
│   ├── Controllers/     # Contrôleurs MVC
│   ├── Core/            # Router, Controller, Database
│   ├── Helpers/         # Fonctions utilitaires
│   ├── Models/          # Modèles de données
│   ├── Repositories/    # Accès base de données
│   ├── Services/        # Logique métier
│   └── Views/           # Vues (layouts, pages, partials)
├── config/              # Configuration (BDD, env)
├── database/            # Schema SQL
├── docker/              # Dockerfile, config Apache
├── public/              # Point d'entrée + assets
├── src/                 # Source CSS Tailwind
├── docker-compose.yml
└── package.json
```

---

## Scripts disponibles

```bash
# Compiler le CSS Tailwind
npm run build:css

# Compiler en mode watch (recompile automatiquement)
npm run watch:css

# Lancer Docker
docker compose up -d

# Arrêter Docker
docker compose down
```

---

## Auteur

Projet réalisé dans le cadre du titre professionnel **Développeur Web et Web Mobile (DWWM)**.
