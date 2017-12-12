README
------


[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9b9246a5-d019-4e38-af18-c407712e919b/big.png)](https://insight.sensiolabs.com/projects/9b9246a5-d019-4e38-af18-c407712e919b)

Html Basics est un ensemble qui vérifie la bonne mise en place des éléments de base pour une bonne intégration HTML qui peuvent être vérifié automatiquement.

L'outil ne vérifie que le code HTML fournit et non le code HTML généré via Javascript.

L'objectif de l'outil est de vérifier les erreurs HTML les plus courantes.

Installation
============

```
git clone https://github.com/truffo/basic-html.git basic-html
cd basic-html
composer install
yarn install
```


Paramétrages
============

Il faut créer un fichier urls.csv dans le répertoire data. Ce fichier est une liste d'url

```
http://example.com/
http://example.com/ma-page

```

Attention, chaque ligne doit être suivi d'un saut de ligne pour être pris en compte.

Execution
============

Pour lancer les tests

```
vendor/bin/phpunit -c phpunit.dist.xmls
```
