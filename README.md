# Jeu du Pendu

Application web du jeu du pendu développée avec Symfony 8, proposant un mode classique et un mode "diabolique".

## Prérequis

- **PHP** >= 8.4
- **Composer**
- **PostgreSQL** >= 18
- **Symfony CLI** (optionnel, pour le serveur de développement)

## Installation

```bash
# Cloner le dépôt
git clone https://github.com/tnicolas28/hangman.git
cd hangman

# Installer les dépendances
composer install

# Configurer la base de données
# Copier le fichier .env et adapter DATABASE_URL
cp .env .env.local
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/hangman?serverVersion=18&charset=utf8"

# Créer la base et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur
symfony server:start
# ou
php -S 127.0.0.1:8000 -t public/
```

## Architecture

Le projet suit une architecture en couches (Domain / Application / Infrastructure) :

```
src/
  Domain/                          # Logique métier pure, aucune dépendance framework
    Enum/GameStatus.php            # Statuts de la partie : Playing, Won, Lost
    Interface/GameInterface.php    # Contrat d'une partie
    Interface/GameRepositoryInterface.php
    Interface/DictionaryInterface.php
    Model/Game.php                 # Partie classique
    Model/EvilGame.php             # Partie diabolique

  Application/                     # Cas d'usage, orchestration
    Service/GameEngine.php         # Création de partie, gestion des tours

  Infrastructure/                  # Framework, persistence, I/O
    Controller/GameController.php  # Routes HTTP
    Entity/GameEntity.php          # Entité Doctrine (partie classique)
    Entity/EvilGameEntity.php      # Entité Doctrine (partie diabolique)
    Entity/Trait/TimestampableTrait.php
    Repository/DoctrineGameRepository.php  # Persistence en BDD
    Repository/SessionGameRepository.php   # Persistence en session
    Service/WordProvider.php       # Dictionnaire depuis fichier texte
    Service/ConstantWordProvider.php  # Dictionnaire fixe (dev)

  Kernel.php
```

## Le jeu

Le pendu est un jeu de devinette : un mot est choisi au hasard et le joueur doit le trouver lettre par lettre avant d'épuiser ses tentatives.

### Règles

- Le joueur dispose de **6 tentatives** maximum
- A chaque tour, le joueur propose une lettre
- Si la lettre est dans le mot, elle est révélée à toutes ses positions
- Si la lettre n'est pas dans le mot, le joueur perd une tentative
- La partie est **gagnée** quand toutes les lettres du mot sont révélées
- La partie est **perdue** quand les 6 tentatives sont épuisées

### Modes de jeu

#### Mode Normal

Le système choisit un mot aléatoirement dans le dictionnaire au début de la partie. Le mot ne change pas : le joueur affronte un pendu classique.

Un **indice** est disponible (utilisable une seule fois par partie) : une lettre non devinée est révélée automatiquement.

#### Mode Diabolique

Le système triche. Au lieu de fixer un mot au départ, il maintient une liste de mots candidats de même longueur. A chaque lettre proposée par le joueur, l'algorithme choisit le groupe de candidats qui **maximise la difficulté** :

- Il partitionne les candidats selon le motif révélé par la lettre
- Il sélectionne le groupe le plus grand, en préférant celui avec le plus de lettres cachées en cas d'égalité
- Le mot "final" n'est déterminé qu'à la fin de la partie

L'indice n'est pas disponible dans ce mode.
