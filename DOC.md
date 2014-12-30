### SWAG Framework Documentation ###

*Cette documentation est pensée pour être adaptée aux membres de notre groupe, de manière à rendre l'utilisation du framework
simple et plus rapide (c'est donc pour cette raison qu'elle sera rédigée en français).*

## Sommaire
- [Routeur](#Routeur)
- [XML](#XML)
  - [Balise simple](##Balise simple)
  - [Balise orpheline](##Balise orpheline)
  - [Balise complexe](##Balise complexe) 

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
- [Balise simple](##Balise simple)
- [Balise orpheline](##Balise orpheline)
- [Balise complexe](##Balise complexe)

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
