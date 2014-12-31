### SWAG Framework Documentation ###

*Cette documentation est pensée pour être adaptée aux membres de notre groupe, de manière à rendre l'utilisation du framework
simple et plus rapide (c'est donc pour cette raison qu'elle sera rédigée en français).*

## Sommaire
- [Routeur](#routeur)
- [XML](#xml)
  - [Balise simple](#balise-simple)
  - [Balise orpheline](#balise-orpheline)
  - [Balise complexe](#balise-complexe) 
- [RSS](#rss)
  - [Création d'un flux RSS](#création-dun-flux-rss)
  - [Ajout d'une entrée](#ajout-dune-entrée)
  - [Génération du flux](#génération-du-flux)

# Routeur

*Pour qu'une route soit accessible, il faut l'ajouter dans le routeur, soit manuellement (comme dans l'example qui suit), 
soit de manière automatisée (là, à vous de voir quelle est la meilleure solution)*

Dans notre exemple, nous allons ajouter deux routes pour le controller *ArticleController* (dans le namespace \App, en suivant
la norme PSR-4)

Une fois les routes ajoutées, on utilise *matchCurrentRequest()* pour récuperer l'adresse et trouver la route correspondante

```php
$router = new Router(); 

// Aide : $router->add('chemin', 'Controlleur', 'fonction à appeller (callback)', 'method');

$router->add('/articles/view', '\App\ArticleController', 'view', 'GET');
$router->add('/articles', '\App\ArticleController', 'index', 'GET');
$router->add('/', '\App\HomeController', 'index', 'GET');

$router->matchCurrentRequest();
```
**Important :** toujours faire en sorte d'ajouter la route par défaut (du controlleur, puis de l'application) en dernier

# XML
- [Balise simple](#balise-simple)
- [Balise orpheline](#balise-orpheline)
- [Balise complexe](#balise-complexe) 

*La classe XML permet de générer des fichiers XML de manière simple et permet une meilleure spécialisation (pour le RSS notamment)*

La contruction d'un document XML générique (mais peut correspondre tout à faire à la génération d'un fichier HTML ou RSS) commence par la définition de la balise d'en-tête (en html c'est le <!DOCTYPE HTML> pour HTML5), cela ce fait comme ceci : 

```php
$header = 'xml version="1.0" encoding="UTF-8"';
$name = 'foo.xml';
$file = new XML($name, $header);
```
Ensuite, pour que l'écriture dans le fichier se déroule correctement, il faudra respecter la forme suivante : 

## Balise simple
```php
$tag = array(
            'title' => 'foo',
            ['option' => 'optionsForFoo',]
            'content' => 'bar'
            );
```
Donnera ainsi : 
```xml
<foo optionsForFoo>bar</foo>
```

## Balise orpheline
```php
$tag = array(
            'title' => 'foo',
            ['option' => 'optionsForFoo']
            );
```
Donnera : 
```xml
<foo optionsForFoo \>
```
## Balise complexe

```php
$tag = array(
            'title' => 'foo', 
            ['option => 'optionsForFoo',]
            'content' => array(
                              'title' => 'bar', 
                              ['option' => 'optionsForBar',]
                              'content' => 'Hello World !'
                              )
            );
```
Donnera : 
```xml
<foo>
  <bar>Hello World !</bar>
</foo>
```

#RSS

- [Création d'un flux RSS](#création-dun-flux-rss)
- [Ajout d'une entrée](#ajout-dune-entrée)
- [Génération du flux](#génération-du-flux)

*La classe RSS permet de générer simplement un flux RSS de type Atom (permettant ainsi une plus grande souplesse et d'être compatible avec tous les nouveaux agrégateurs RSS, même si la syntaxe est globalement identique à celle de RSS 2.0), elle hérite de la classe [XML](#xml) expliquée précédemment puisqu'elle génère un document XML particulier.*

L'intégralité de l'implémentation a été fait en respectant au mieux et toujours de la manière la plus simplifiée possible les spécifications Atom (disponible [ici](http://atomenabled.org/developers/syndication/)), le but principal étant de pouvoir ajouter rapidement de nouvelles fonctionnalités si le besoin s'en fait sentir (de nouvelles balises par exemple)

## Création d'un flux RSS

Le constructeur va initialiser l'en-tête du document avec toutes les informations fournies *(le choix d'utiliser une liste de paramètre au lieu d'un tableau se justifie par une utilisation plus simple avec un IDE et une documentation plus précise par la suite)*.

Exemple (disponible dans le fichier *tests/RSS/RSSTest.php*) : 
```php
/* Nom du fichier de destination (peut contenir le chemin relatif (ex : ../feed/ComplexRSSTest.rss) */
$name = 'ComplexRSSTest.rss'; 

/* Url du site de provenance du flux */
$url = 'http://unicorn.ovh';

/* Titre du flux */
$title = 'Articles about Unicorn - Unicorn are real!'; 

/* Image (utilisée par la majorité des agrégateurs en tant qu'icône du flux) */
$img = 'http://upload.wikimedia.org/wikipedia/commons/8/8f/Historiae_animalium_1551_De_Monocerote.jpg'; 

/* Auteur du flux (certains agrégateurs s'en servent pour trier les flux, mais c'est plutôt rare, 
*  ça fait partie des recommandations d'Atom, donc on le met */
$author = 'AFS';

$feed = new RSS($name, $url, $title, $img, $author);
```

## Ajout d'une entrée 

Les entrées permettent de séparer chaque nouveau contenu dans un flux RSS, ils doivent avoir un titre, un lien (vers la page web correspondante), un bref résumé du contenu et une date au format "Zulu" (c'est à dire en respectant la norme [ISO 8601](http://fr.wikipedia.org/wiki/ISO_8601) utilisée par tous les flux RSS et dans l'informatique en général)

Exemple (toujours tiré de *tests/RSS/RSSTest.php*) :
```php
/* Titre de l'entrée */
$title = 'Proof that unicorn exists';

/* Lien vers la page web correspondante */
$link = 'https://www.youtube.com/watch?v=HHQIXCs4d98';

/* Résumé de l'entrée (ici la vidéo) */
$summary = 'This is a legit proof that unicorn are real, they exist I have a proof now !';

/* Date de création de l'entrée (utilisation de la classe DateTime pour les tests mais elle doit être 
* la date de création de l'entrée (ex : date de publication de l'article correspondant sur le site))
*/
$date = new \DateTime();
$dateU = $date->format('Y-m-d\TH:i:s\Z');

/* Ajout de l'entrée au fichier que l'on souhaite créer */
$feed->addEntry($title, $link, $summary, $dateU);
```

## Génération du flux

Une fois toutes les entrées saisies, on génére notre flux (par la création du fichier correspondant) comme ceci : 

```php
$feed->create(); // Ce n'est pas très compliqué :)
```
La méthode va ainsi appeller la méthode write de sa classe mère ([XML](#xml)) pour générer le document XML correspondant, bien indenté complet :)
