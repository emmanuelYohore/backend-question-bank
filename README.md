# backend-question-bank

Description
-----------

Backend API développée avec Laravel 12 (PHP 8.1+) pour gérer une "banque de questions"—entités, enquêtes, items, réponses et utilisateurs. Le service expose une API REST (JSON) consommée par le frontend (Vite + Vue) et par d'autres clients.

Objectifs du projet
-------------------

- Gérer des banques de questions structurées par enquêtes et items
- Permettre la création, modification, suppression et consultation des ressources
- Fournir un système d'authentification et d'autorisation (roles)
- Offrir des endpoints pour récupérer des jeux de questions filtrés et paginés
- Supporter l'import/export et la persistance fiable (migrations)

Technologies principales
-----------------------

- Laravel (Eloquent ORM, routing, middleware)
- PHP 8.1+
- Base de données relationnelle (MySQL)


Architecture & organisation
---------------------------

Le projet suit une architecture MVC classique enrichie de répertoires pour les responsabilités suivantes :

- `app/Models` — Modèles Eloquent représentant le modèle de domaine (voir section "Modèle de domaine")
- `app/Http/Controllers` — Contrôleurs d'API qui orchestrent la logique d'application et retournent des ressources JSON
- `app/Http/Requests` — Objets de validation pour séparer la validation des contrôleurs
- `app/Repositories` —  couche d'accès aux données pour encapsuler requêtes complexes
- `app/Exceptions` — Exceptions métiers personnalisées
- `app/Providers` — Providers pour lier des services et configurer le container
- `routes/api.php` — Points d'entrée de l'API
- `database/migrations` et `database/seeders` — gestion du schéma et données initiales

Modèle de domaine (entités principales)
-------------------------------------

Voici un résumé des entités centrales et de leurs relations :

- `User` — représente un utilisateur (admins, respondants, etc.). Relation : `User` 1:N `Enquete` (créateur), `User` 1:N `Reponse`.
- `Bank` / `EnqueteBank` — regroupe plusieurs `Enquete` ou sert de conteneur logique pour les questions.
- `Enquete` — une enquête ou un ensemble de questions ; `Enquete` 1:N `Item`.
- `Item` — question ou élément de banque (peut contenir médias, contraintes). `Item` N:1 `FormatReponse`.
- `FormatReponse` — type de réponse attendu (choix unique, multiple, texte, evn).
- `BankItem` / `BankItemItem` — tables de relation pour associer items à banques si nécessaire.
- `Reponse` — réponses soumises par un `User` pour une `Enquete` / `Item`.

Relations typiques :

- `Enquete` hasMany `Item`
- `Item` belongsTo `FormatReponse`
- `User` hasMany `Reponse`

Flux d'une requête (exemple création d'une enquête)
--------------------------------------------------

1. Requête POST `/api/enquetes` authentifiée
2. `EnqueteController@store` valide la requête via `StoreEnqueteRequest`
3. Création du modèle `Enquete` et association des `Item` par transactions
4. Événement `EnqueteCreated` émis (si besoin) -> Listeners (envoi de notifications, indexation)
5. Réponse JSON avec ressource `EnqueteResource`

Authentification & autorisation
--------------------------------

- JWT pour l'authentification des API

Cache, files & queues
---------------------


- Files : stockage local (`storage/app`) et liens publics via `php artisan storage:link`
- Queues : jobs et listeners (envoyer emails, traitements d'import) configurables via `QUEUE_CONNECTION`


CI / Déploiement
-----------------

- Exemple de workflow CI : lint PHP, exécuter tests, build assets (si concerné), déployer
- Utiliser `composer install --no-dev --optimize-autoloader` en production
- Pour la migration automatisée : `php artisan migrate --force` (via pipeline avec sauvegarde DB)



