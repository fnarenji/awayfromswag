AwayFromSwag
============

Pour ce site, nous avons conçus un micro framework que nous utilisons pour le système de route, la base de données les formulaires etc.
Nous utilisons Composer pour la gestion des dépendances: il faut donc "composer install".

Ce projet a été réalisé par:
- Anthony LOROSCIO
- Loick MAHIEUX
- Thomas MUNOZ
- Floran NARENJI-SHESHKALANI
- Loïc PAULETTO

Divers warnings:
- Les accès de base de données fournis dans la configuration correspondent à une BD hébergée sur un de nos dédiés personnels. Le système de messagerie utilise celle-ci pour les métadonnées des conversations, mais repose sur des fichiers locaux (app/converations/) pour le stockage des messages mêmes. Il est donc normal qu'une exception soit levée si le fichier n'est pas présent (par exemple si vous créez une conversation sur votre copie locale sur site et qu'un autre enseignant tente de l'ouvrir sur une autre machine)



Le site est visible en ligne à l'adresse https://srv0.sknz.info:3735.

WHERE IS THE SWAG ?
