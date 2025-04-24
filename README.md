# Flemmar - API de Scrapping des Torrents(Développement  en cours)

## Description

**Flemmar API** est une application Symfony dédiée au scrapping de torrents depuis des sites comme [Torrent9](https://www.torrent9.lat). L'objectif est de récupérer des informations sur les films et séries disponibles, comme les liens magnet, les seeds, les leechers, la taille, les catégories, et plus encore. Ces données sont ensuite exposées via une API et pourront être utilisées dans une application mobile et web dédiée au streaming de ces contenus.

**Le but final de ce projet est de créer une plateforme qui permet aux utilisateurs de streamer des films et séries directement depuis l'application, en utilisant les torrents récupérés.**

Rejoignez ce projet pour contribuer à la création d'une plateforme innovante de streaming.

## Fonctionnalités

- Scraping de torrents sur des sites comme **Torrent9**, permettant d'extraire des informations détaillées sur les films et séries.
- Récupération des liens **magnet**, le nombre de **seeds** et de **leechers**, la **taille du torrent**, **date d'ajout**, **catégories**, **description** des films, etc.
- Exposition des données via une **API RESTful** que vous pouvez intégrer dans des applications web ou mobiles.
- **Streaming des films et séries** à travers une interface web et mobile intuitive, avec des fonctionnalités comme la lecture en qualité HD, la gestion des sous-titres, et plus encore.
  
**Rejoignez l'aventure et aidez à créer une application moderne qui permettra aux utilisateurs de découvrir et de streamer leurs films et séries préférés directement depuis l'application !**

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- PHP 8.0+ (ou version plus récente)
- Composer
- Symfony CLI (facultatif mais recommandé)
- Une base de données (si vous souhaitez stocker les données des torrents)

## Installation

1. Clonez ce dépôt dans votre répertoire local :

   ```bash
   git clone https://github.com/BlakvGhost/flemmar-api.git
   cd flemmar-api
   ```

2. Installez les dépendances du projet avec Composer :

```bash
composer install
```

3. (Facultatif) Configurez votre base de données dans le fichier .env si vous souhaitez persister les résultats des scrapes.

Lancez les migrations si vous avez configuré une base de données (si vous avez des entités à persister) :

```bash
php bin/console doctrine:migrations:migrate
```

## Utilisation

Exécution de la commande de Scrapping
Le projet inclut une commande Symfony qui permet de scraper les torrents d'un site comme Torrent9. Voici comment l'utiliser :

1. Exécutez la commande pour scraper les films :

```bash
php bin/console app:scrape-torrents
```

Cette commande va récupérer tous les torrents listés sur Torrent9 dans la catégorie "Films" et extraire les informations suivantes pour chaque torrent :

- Titre

- Magnet Link

- Nombre de Seeds

- Nombre de Leechers

- Taille du torrent

- Date d'ajout

- Catégorie principale et sous-catégorie

- Description du film

2. Vous pouvez personnaliser la commande pour scraper d'autres catégories en modifiant l'URL cible dans le code.

## Test de l'API

Si vous avez configuré un contrôleur pour servir les données via une API (par exemple, GET /api/torrents), vous pouvez tester l'API en accédant à cette URL :

```bash
<http://localhost:8000/api/torrents>
```

Cela renverra la liste des torrents récupérés par la commande de scrapping.

## Auteurs

- [BlakvGhost](https://username-blakvghost.com)
- [Vous?](https://github.com/BlakvGhost/flemmar-api#README)
