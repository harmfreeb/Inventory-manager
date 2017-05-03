<?php
require_once('includes/load.php');


// Routing

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);


switch ($request_uri[0]) {
    // Liste des produits
    case '/':
        require 'views/pages/products.php';
        break;
    // Ajouter un produit
    case '/ajouter-un-produit':
        require 'views/pages/add-product.php';
        break;
    //Supprimer un produit
    case '/supprimer-un-produit':
        $delete = deleteById($_GET['id']);
        if($delete){ redirect('/?delete=success'); }
        else {  redirect('/?delete=error'); }
        break;
    //Editer un produit
    case '/editer-un-produit':
        if(!isset($_GET['id'])){
            redirect('/404');
        }
        require 'views/pages/edit-product.php';
        break;
    //Rechercher et filtrer
    case '/search':

        echo filter($_GET['search'],$_GET['category'],$_GET['statut']);

        break;
    // 404 error
    default:
        header('HTTP/1.0 404 Not Found');
        require 'views/pages/404.php';
        break;
}