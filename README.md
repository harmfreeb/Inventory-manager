
Web application pour gérer l'inventaire d'un magasin de disque 

## Features
- Ajouter/Modifier/Supprimer des CD 
- Vérification des champs pour l'ajout et la modification des CD
- Protection des input contre les injections SQL ( htmlspecialchars, mysql_real_escape_string, etc... )
- Ajout via l'API d'Amazon. La requête récupérera Titre / Artiste / Cover
- Systeme de flash à chaque action

## Installation 
- Installer la BDD
- includes/config.php (Modifier URL, et les infos de la BDD, et d'AWS)

## Future features
- Gestion des catégories
- Alertes ( stock bas etc...)


## Tools used
- Javascript
- PHP
- MySQL
- Jquery
- Bootstrap 3 

