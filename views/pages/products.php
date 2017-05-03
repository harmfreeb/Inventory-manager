<?php
$title_page = 'Produits';
include ('views/partials/header.php');
require_once('includes/load.php');
$products = productsAll();


echo (issetor($_GET['delete']) == 'success' ? flash('success', 'Le CD a bien été supprimé') : '');
echo (issetor($_GET['delete']) == 'error' ? flash('danger', 'Suite à une erreur le CD n\'a pas été supprimé') : '');

?>

<div class="row">
    <div class="col-md-12">
        <div id="first-panel" class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-search"></span>
                    <span>Rechercher et filtrer</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="input-group">
                                <input type="search" class="search-input form-control" placeholder="Rechercher">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="filter">
                                <label class="checkbox-inline"><input type="checkbox" value="'Rock'" checked>Rock</label>
                                <label class="checkbox-inline"><input type="checkbox" value="'Pop'" checked>Pop</label>
                                <label class="checkbox-inline"><input type="checkbox" value="'Electro'" checked>Electro</label>
                                <label class="checkbox-inline"><input type="checkbox" value="'Rap'" checked>Rap</label>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="statut">
                                <label class="checkbox-inline" for="stock"><input id="stock" type="radio" name="statut" value="'En stock'" >En stock</label>
                                <label class="checkbox-inline" for="appro"><input id="appro" type="radio" name="statut" value="'Approvisionnement en cours'" >Approvisionnement en cours</label>
                                <label class="checkbox-inline" for="rupt"><input id="rupt" type="radio" name="statut" value="'En rupture de stock chez le fournisseur'" >En rupture de stock chez le fournisseur</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="/ajouter-un-produit" class="btn btn-primary">Ajouter un nouveau CD</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 15%">Cover</th>
                        <th class="text-center" style="width: 15%;"> Titre </th>
                        <th class="text-center" style="width: 13.3%;"> Artiste </th>
                        <th class="text-center" style="width: 13.3%;"> Catégorie </th>
                        <th class="text-center" style="width: 5%;"> Quantité </th>
                        <th class="text-center" style="width: 5%;"> Prix </th>
                        <th class="text-center" style="width: 13.3%;"> Statut </th>
                        <th class="text-center" style="width: 5%;"> Modifier </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product):?>
                        <tr>
                            <td>
                                <img class="img-thumbnail" src="<?= $product['cover']; ?>" alt="Cover <?= $product['title']; ?>">
                            </td>
                            <td class="text-center"> <?= $product['title']; ?></td>
                            <td class="text-center"> <?= $product['artist']; ?></td>
                            <td class="text-center"> <?= $product['categorie'] ?></td>
                            <td class="text-center"> <?= $product['quantity'] ?></td>
                            <td class="text-center"> <?= $product['price'] . ' €' ?></td>
                            <td class="text-center"> <?= $product['statut'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="editer-un-produit?id=<?= (int)$product['id']?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="supprimer-un-produit?id=<?= (int)$product['id']?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<?php

include ('views/partials/footer.php');
