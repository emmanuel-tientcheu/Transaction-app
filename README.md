
# Projet Laravel et React avec Passport

Ce projet est une application full-stack composée d'un backend Laravel avec Laravel Passport pour l'authentification API, et un frontend React pour l'interface utilisateur.

## Prérequis

Avant de commencer, assurez-vous d'avoir les outils suivants installés :

- **PHP** (version 8.1 ou supérieure)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** et **npm** (gestionnaire de paquets JavaScript)
- **MySQL** ou **MariaDB**
- **Git** (si vous clonez le projet depuis un dépôt Git)

## Installation du backend (Laravel)

1. **Clonez le projet** dans le répertoire de votre choix :
   
   ```bash
   git clone <URL-de-votre-depot>
   cd <nom-du-projet>
   ```

2. **Installez les dépendances PHP** avec Composer :

   ```bash
   composer install
   ```

3. **Créez le fichier `.env`** à partir du fichier `.env.example` :
   
   ```bash
   cp .env.example .env
   ```

4. **Générez la clé d'application Laravel** :

   ```bash
   php artisan key:generate
   ```

5. **Configurez votre base de données** dans le fichier `.env` :

   Assurez-vous que les paramètres suivants dans le fichier `.env` correspondent à votre configuration de base de données MySQL :

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nom_de_votre_base
   DB_USERNAME=utilisateur
   DB_PASSWORD=mot_de_passe
   ```

6. **Exécutez les migrations** pour créer les tables de la base de données :

   ```bash
   php artisan migrate
   ```

7. **Installez Passport pour l'authentification** :

   Laravel Passport est utilisé pour gérer l'authentification API via OAuth2. Exécutez les commandes suivantes pour configurer Passport :

   ```bash
   php artisan passport:install
   ```

   Cela générera les clés nécessaires pour OAuth2 et ajoutera les clients par défaut.

8. **Créer les utilisateurs pour l'authentification** :

   Vous pouvez utiliser les deux utilisateurs par défaut fournis pour vous connecter à l'API immédiatement. Ces utilisateurs sont créés lors de l'exécution du seeder. Par défaut, vous pouvez vous connecter avec les informations suivantes :

   - **Utilisateur 1 :**
     - Email : `john@example.com`
     - Mot de passe : `password123`
   
   - **Utilisateur 2 :**
     - Email : `jane@example.com`
     - Mot de passe : `password123`

   Exécutez le seeder pour les utilisateurs :

   ```bash
   php artisan db:seed
   ```
9. **Lancez les tests**

   ```bash
      php artisan test   
      ```

10. **Lancez le serveur Laravel** :

   Pour démarrer le serveur de développement de Laravel, exécutez :

   ```bash
   php artisan serve
   ```

   Cela lancera l'application sur `http://localhost:8000`.

## Installation du frontend (React)

1. **Accédez au dossier frontend** :

   Si votre projet React se trouve dans un sous-dossier, accédez à ce dossier :

   ```bash
   cd frontend
   ```

2. **Installez les dépendances JavaScript** avec npm :

   ```bash
   npm install
   ```

3. **Lancez le serveur de développement React** :

   Pour démarrer le serveur de développement React, exécutez :

   ```bash
   npm start
   ```

   Cela lancera l'application sur `http://localhost:3000`.

## Structure du projet backend (Laravel)

Le backend est structuré en suivant les principes de la **Clean Architecture**, avec quelques ajustements pour gagner du temps. Bien que j'aie cherché à maintenir une architecture propre, j'ai utilisé **les modèles Eloquent** pour certaines parties du projet afin de simplifier et accélérer le développement et compte tenu de l'utilisation de modèles éloquents, il est nécessaire de faire des tests E2E, donc seuls des tests de bout en bout ont été effectués.

### Principales caractéristiques du backend :

- **Authentification** : Utilisation de **Laravel Passport** pour gérer l'authentification OAuth2 des utilisateurs via des tokens API.
- **Controllers** : Les contrôleurs gèrent la logique des actions pour les routes de l'API.
- **Models** : Utilisation des modèles **Eloquent** pour l'interaction avec la base de données. Bien que la Clean Architecture recommande des entités distinctes pour chaque couche, l'utilisation d'Eloquent a été privilégiée ici pour sa simplicité.
- **Services** : Une couche de services est utilisée pour encapsuler la logique métier, bien que certaines parties du projet utilisent directement les modèles Eloquent pour gagner du temps.

## Test de l'API

Pour tester l'API, vous pouvez utiliser **Postman** ou **Insomnia**. Voici quelques points de terminaison utiles pour démarrer :

1. **POST /api/login** : Pour obtenir un token d'authentification. Passez `email` et `password` dans le corps de la requête.
   Exemple :

   ```json
   {
     "email": "john@example.com",
     "password": "password123"
   }
   ```

2. **GET /api/user** : Pour récupérer les informations de l'utilisateur authentifié (nécessite un token d'authentification).

   **Headers** :
   ```bash
   Authorization: Bearer <votre_token>
   ```

## Conclusion

Ce projet fournit une base solide avec Laravel pour le backend, utilisant Laravel Passport pour l'authentification via OAuth2. Le frontend est construit avec React et se connecte à l'API pour l'authentification et la gestion des données.

N'hésitez pas à personnaliser et étendre ce projet selon vos besoins.
