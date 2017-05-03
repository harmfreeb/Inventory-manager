<?php
$title_page = 'Editer un produit';
include ('views/partials/header.php');
$cd = findById((int)$_GET['id']);

global $cover_from_upload;

if(isset($_POST['edit-cd'])) {
    unset($cd);


    $tabExt = array('jpg', 'png', 'jpeg');
    $infosImg = array();

    if (!empty($_FILES['cover']['name'])) {
        if(strpos($_POST['coverURL'], "uploads") !== false){
            unlink($_POST['coverURL']); //delete previous cover
        }

        $extension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $tabExt)) {
            $infosImg = getimagesize($_FILES['cover']['tmp_name']);
            if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['cover']['tmp_name']) <= MAX_SIZE)) {
                $image = md5(uniqid()) . '.' . $extension;
                if (move_uploaded_file($_FILES['cover']['tmp_name'], TARGET . $image)) {
                    $cover_from_upload = TARGET . $image;
                } else {
                    $fields['error'] = 'Problème lors de l\'upload !';
                }
            } else {
                $fields['error'] = 'Veillez à que la cover soit d\'une taille inférieur à 800*800 et qui pèse moins de 1mo';
            }
        } else {
            $fields['error'] = 'Veillez à que la cover soit en jpg, png, ou jpeg';
        }
    }


    $data = array('id' => $_POST['edit-cd'], 'title' => $_POST['title'], 'artist' => $_POST['artist'], 'category' => $_POST['category'], 'coverURL' => (issetor($cover_from_upload) ? $cover_from_upload : $_POST['coverURL']), 'quantity' => $_POST['quantity'], 'price' => $_POST['price'], 'statut' => $_POST['statut']);

    $fields = checkAddCD($data);

    if (empty($fields['error'])) {
        if (updateProducts($fields) == true) {
            echo flash('success', 'Le CD a bien été modifié dans la base de donnée');
        } else {
            echo flash('danger', 'Désolé, une erreur est survenue');
        }
    } else {
        $error = implode("<br>", $fields['error']);
        echo flash('danger', issetor($error));
    }


}

if(isset($_POST['reset'])) {
    $cd = findById('products',(int)$_GET['id']);
}


?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-pencil"></span>
                <span>Ajouter un CD</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-3 cover-preview thumbnail">
                <img src="<?= (issetor($cd['cover']) ? $cd['cover'] : issetor($fields['coverURL']))  ?>" alt="Prévisualisation cover ">
            </div>
            <div class="col-md-9">
                <form method="post" action="/editer-un-produit?id=<?= (issetor($fields['id']) ? $fields['id'] : issetor($cd['id'])) ?>" enctype="multipart/form-data">

                    <!-- Title -->
                    <div class="form-group <?= issetor($fields['title']) ?>">
                        <div class="input-group">
                              <span class="input-group-addon">
                               <i class="glyphicon glyphicon-th-large"></i>
                              </span>
                            <input type="text" value="<?= issetor($cd['title']).(issetor($fields['title']) == 'has-error' ? '' : $fields['title']) ?>" class="form-control" name="title" placeholder="Titre">
                        </div>
                    </div>

                    <!-- Artist -->
                    <div class="form-group <?= issetor($fields['artist']) ?>">
                        <div class="input-group">
                              <span class="input-group-addon">
                                <i class="glyphicon glyphicon-th-large"></i>
                              </span>
                            <input type="text" value="<?= issetor($cd['artist']).(issetor($fields['artist']) == 'has-error' ? '' : $fields['artist']) ?>" class="form-control" name="artist" placeholder="Artiste">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">

                            <!-- Categories -->
                            <div class="col-md-6 <?= issetor($fields['category']) ?> ">
                                <select class="form-control" name="category" >
                                    <option value="">Genre</option>
                                    <option value="Rock" <?= (issetor($cd['categorie']).issetor($fields['category'])  == 'Rock' ? 'selected' : '') ?>>Rock</option>
                                    <option value="Pop" <?= (issetor($cd['categorie']).issetor($fields['category']) == 'Pop' ? 'selected' : '') ?>>Pop</option>
                                    <option value="Electro" <?= (issetor($cd['categorie']).issetor($fields['category'])  == 'Electro' ? 'selected' : '') ?>>Electro</option>
                                    <option value="Rap" <?= (issetor($cd['categorie']).issetor($fields['category'])  == 'Rap' ? 'selected' : '') ?>>Rap</option>
                                </select>
                            </div>


                            <!-- Cover -->
                            <div class="col-md-6">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-picture"></i>
                                        </span>
                                    <input type='file' name='cover' class='form-control cover-upload'>
                                    <input type="hidden" name="coverURL" value="<?= (issetor($fields['coverURL']) ? $fields['coverURL'] : issetor($cd['cover'])) ?>">

                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="form-group">
                        <div class="row">
                            <!-- Quantité -->
                            <div class="col-md-4 <?= issetor($fields['quantity']) ?>">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="glyphicon glyphicon-shopping-cart"></i>
                                        </span>
                                    <input type="number" value="<?= issetor($cd['quantity']).(issetor($fields['quantity']) == 'has-error' ? '' : $fields['quantity']) ?>" class="form-control" name="quantity"  placeholder="Quantité">
                                </div>
                            </div>
                            <!-- Prix de vente -->
                            <div class="col-md-4 <?= issetor($fields['price']) ?>">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="glyphicon glyphicon-usd"></i>
                                        </span>
                                    <input type="number" value="<?= issetor($cd['price']).(issetor($fields['price']) == 'has-error' ? '' : $fields['price']) ?>"class="form-control" name="price" step="any" placeholder="Prix">
                                </div>
                            </div>
                            <!-- Statut -->
                            <div class="col-md-4 <?= issetor($fields['statut']) ?>">
                                <select class="form-control" name="statut">
                                    <option value="En stock"<?= ((issetor($cd['statut'])  == 'En stock' || (issetor($fields['statut'])  == 'En stock')) ? 'selected' : '') ?>>En stock</option>
                                    <option value="Approvisionnement en cours"<?= ((issetor($cd['statut'])  == 'Approvisionnement en cours' || (issetor($fields['statut']))  == 'Approvisionnement en cours') ? 'selected' : '') ?>>Approvisionnement en cours</option>
                                    <option value="En rupture de stock chez le fournisseur"<?= ((issetor($cd['statut'])  == 'En rupture de stock chez le fournisseur' || (issetor($fields['statut'])  == 'En rupture de stock chez le fournisseur')) ? 'selected' : '') ?>>En rupture de stock chez le fournisseur</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="reset" class="btn btn-info">Restaurer les valeurs par défaut</button>
                    <button type="submit" name="edit-cd" value="<?= $_GET['id'] ?> "class="btn btn-warning">Modifier le CD</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

include ('views/partials/footer.php');
