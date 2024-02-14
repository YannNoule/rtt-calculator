# Installation du Projet Laravel

Ce guide vous aidera à installer et à configurer mon test technique.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

- PHP (version 7.3 ou supérieure)
- Composer
- Un serveur web comme Apache ou Nginx (optionnel pour l'utilisation du serveur de développement intégré de PHP)

## Installation

Suivez ces étapes pour installer le projet :

1. **Cloner le dépôt Git**


    git clone https://github.com/YannNoule/rtt-calculator.git/


2. **Installer les dépendances**

Accédez au dossier du projet et installez les dépendances PHP avec Composer.

    cd rtt-calculator

    composer install


3. **Générer la clé d'application**

Laravel nécessite une clé d'application pour sécuriser les sessions et les données cryptées. Générez cette clé avec la commande suivante :

    php artisan key:generate

4. **Lancer le serveur de développement (optionnel)**

Si vous n'utilisez pas de serveur web comme Apache ou Nginx, vous pouvez utiliser le serveur de développement intégré de PHP pour lancer l'application.

    php artisan serve

Cela démarrera un serveur de développement accessible à l'adresse `http://localhost:8000`.

