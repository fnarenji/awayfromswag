### SWAG Framework Documentation ###

*Cette documentation est pensée pour être adaptée aux membres de notre groupe, de manière à rendre l'utilisation du framework
simple et plus rapide (c'est donc pour cette raison qu'elle sera rédigée en français).*

## Sommaire
- [Routeur](#Routeur)

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
