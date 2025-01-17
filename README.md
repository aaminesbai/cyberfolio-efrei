# Projet « Cyberfolio »

## Compte administrateur

- URL du *back-office* : [http://localhost:8000/connexion-admin](/connexion-admin)
- Identifiant : `aminesbai1`
- Mot de passe : `motdepasse123`

## État d'avancement

- Structure front-end : 100 % terminée
- Fonctionnalités back-end : 85 % (upload presque fini, manque register)

## Difficultés rencontrées et solutions

1. **Gestion des fichiers statiques (CSS/JS)**  
   - **Problème** : Le lien entre les fichiers statiques et le rendu final posait problème en environnement local
   - **Solution** : Utilisation du paramètre `asset()` dans Twig

2. **Gestion des recommandations dynamiques**  
   - **Problème** : Les données des recommendations ne s’affichaient pas correctement après leur insertion via le panel admin
   - **Solution** : Revue des relations dans la base de données et ajustement des boucles `for` dans Twig

## Bilan des acquis

- Approfondissement des connaissances en **Twig** pour gérer des contenus dynamiques
- Utilisation de **Symfony** pour les routes, les formulaires, les services, etc  
- Mise en place d’une architecture back-end avec des contrôleurs bien organisés
- Gestion des assets dans un projet Symfony (CSS, JS, images)

## Remarques complémentaires

- **Améliorations possibles** : Ajouter plus de contenu modifiale dans le panel et un clean-up visuel pour rendre l’interface utilisateur meilleure

### Installation

1. Cloner le repo:

    ```bash
    git clone https://github.com/aaminesbai/cyberfolio-efrei
    ```

2. Installer les dépendances:

    ```bash
    cd cyberfolio-efrei
    composer install
    ```

3. Set up la base de donnée:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```
    **OU**
    Importer le fichier SQL situé dans ``/data/`` dans votre BDD.
    
3. Rajouter les valeurs dans le .env:
    Rajouter le contenu suivant dans le `.env` : (ou `.env.local`)
    ```bash
    APP_ENV=dev
    APP_SECRET=SECRET_CYBERFOLIO
    DATABASE_URL="mysql://root:root@127.0.0.1:3306/projet_cyberfolio?serverVersion=15&charset=utf8mb4" // Url pour phpMyAdmin simple
    ```
    Remplacer `DATABASE_URL` dans le **.env** par l'URL de connexion à sa BDD.

4. Démarrer le serveur:

    ```bash
    php bin/console server:run
    ```

5. Accéder l'app à [http://localhost:8000](http://localhost:8000).
