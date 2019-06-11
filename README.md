# PRISM - gestion

Logiciel de gestion de matériel pour le PRISM de l'IUT Nancy-Charlemagnes à l'occasion d'un stage de 12 semaines.

Accès TRELLO : https://trello.com/b/66d2jHhV/prism-gestion


## Installation et lancement du projet

1. Cloner le git: `git clone git@bitbucket.org:CrysisWar/prism-gestion.git`
2. Dans le dossier `prism-gestion/prism/src`, lancer dans un terminal la commande
`$ php composer.phar install`
3. La base de données se trouve dans le dossier `sql/database.sql`, importez la avec votre logiciel de prédilection, puis modifiez dans le fichier `prism-gestion/prism/src/bootstrap/conf.ini` les accès à votre base.
4. Dans le dossier `prism-gestion/prism/prismvue`, lancer dans un terminal la commande
`$ npm install`
5. Lancer dans un terminal `$ vue ui`
6. Développez tout votre saoul !



## Mise en production

1. Dans le fichier `prism-gestion/prism/src/bootstrap/app.php`,
changer la valeur de la ligne 7 en false: `'displayErrorDetails' => false,`
2. Dans vue ui, lancer un "build". Le dossier `./dist/` créé doit être renommé en `./app/`.
3. Déplacez le dossier `./app/` dans le dossier public contenant les dossiers `api` et `apidoc`.
4. Placez dans le serveur web les dossiers `prism-gestion/prism/public` et `prism-gestion/prism/src`.
5. Lancer composer pour que les vendors du dossier src soient créés.
6. Importer dans le serveur sql la base de données `prism-gestion/sql/database.sql`.
7. Modifier le fichier `prism-gestion/prism/src/bootstrap/conf.ini` pour correspondre au serveur sql.
