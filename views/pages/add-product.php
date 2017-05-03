<?php
$title_page = 'Ajouter un produit';
include ('views/partials/header.php');

global $cover_from_upload;

//Amazon request
if(isset($_POST['ean'])){

    if (getMetadataFromAmazon($_POST['ean']) === null){
        $error_amazon = 'La requête a échoué. Veuillez saisir manuellement les informations du CD';
        echo flash('danger', $error_amazon);
    } else {
        $cd = json_decode(getMetadataFromAmazon($_POST['ean']));
        $fields = checkAmazonRequest($cd);
        $cover_from_amazon  = ($cd->cover == '' ? 'uploads/cover/no_image.jpg' : $cd->cover);
        $success_amazon = 'Les informations ont bien été insérées. Merci de remplir les champs manquants.';
        echo flash('success', $success_amazon);

    }
}


if(isset($_POST['add_product'])) {
    $tabExt = array('jpg', 'png', 'jpeg'); //Extension allowed to cover
    $infosImg = array();
    //On upload on cover
    if (!empty($_FILES['cover']['name'])) {
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
    //Array for check all informations
    $data = array('title' => $_POST['title'], 'artist' => $_POST['artist'], 'category' => $_POST['category'], 'coverURL' => issetor($cover_from_upload).(issetor($_POST['coverURL']) ? $_POST['coverURL'] : ''), 'quantity' => $_POST['quantity'], 'price' => $_POST['price'], 'statut' => $_POST['statut']);

    $fields = checkAddCD($data);


    if (empty($fields['error'])) {
        if (addProducts($fields) == true) {
            foreach ($fields as $key => $value) {
                unset($fields[$key]);
            }
            unset($cover);
            echo flash('success', 'Le CD a bien été inséré dans la base de donnée');
        } else {
            echo flash('danger', 'Désolé, une erreur est survenue');
        }
    } else {
        $error = implode("<br>", $fields['error']);
        echo flash('danger', issetor($error));
    }
}

?>



<?php if (!isset($success_amazon)):  ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-barcode"></span>
                    <span>Scanner code-barres CD</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form method="post" action="/ajouter-un-produit" class="clearfix">
                        <div class="form-group">
                            <div class="input-group">
                              <span class="input-group-addon">
                               <i class="glyphicon glyphicon-th-large"></i>
                              </span>
                                <input type="text" class="form-control" name="ean" placeholder="EAN-13" autofocus>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-pencil"></span>
                    <span>Ajouter un CD</span>
                </strong>
            </div>
            <div class="panel-body">
                <div class="col-md-4 cover-preview thumbnail">
                    <img src="<?= (issetor($fields['coverURL']) == '' ? issetor($cover_from_amazon) : $fields['coverURL']) ?>" alt="Prévisualisation cover ">
                </div>
                <div class="col-md-8">
                    <form method="post" action="/ajouter-un-produit" enctype="multipart/form-data">

                        <!-- Title -->
                        <div class="form-group <?= issetor($fields['title']) ?> ">
                            <div class="input-group">
                              <span class="input-group-addon">
                               <i class="glyphicon glyphicon-th-large"></i>
                              </span>
                                <input type="text" value="<?= (issetor($fields['title']) == 'has-error' ? '' : $fields['title']) ?>" class="form-control" name="title" placeholder="Titre">
                            </div>
                        </div>

                        <!-- Artist -->
                        <div class="form-group <?= issetor($fields['artist'])?> ">
                            <div class="input-group">
                              <span class="input-group-addon">
                                <i class="glyphicon glyphicon-th-large"></i>
                              </span>
                                <input type="text" value="<?= (issetor($fields['artist']) == 'has-error' ? '' : $fields['artist'])?>" class="form-control" name="artist" placeholder="Artiste">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">

                                <!-- Categories -->
                                <div class="col-md-6 <?= issetor($fields['category']) ?> ">
                                    <select class="form-control" name="category" >
                                        <option value="">Genre</option>
                                        <option value="Rock" <?= (issetor($fields['category'])  == 'Rock' ? 'selected' : '') ?>>Rock</option>
                                        <option value="Pop" <?= (issetor($fields['category'])  == 'Pop' ? 'selected' : '') ?>>Pop</option>
                                        <option value="Electro" <?= (issetor($fields['category'])  == 'Electro' ? 'selected' : '') ?>>Electro</option>
                                        <option value="Rap" <?= (issetor($fields['category'])  == 'Rap' ? 'selected' : '') ?>>Rap</option>
                                    </select>
                                </div>

                                <!-- Cover -->
                                <div class="col-md-6 <?= issetor($fields['coverURL']) ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-picture"></i>
                                        </span>
                                        <input type='file' name='cover' class='form-control cover-upload' <?= issetor($fields['cover-display']) ?> >
                                        <input type="hidden" name="coverURL" value="<?= (issetor($fields['coverURL']) == '' ? issetor($cover_from_amazon) : $fields['coverURL']) ?>">

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
                                        <input type="number" value="<?= issetor($fields['quantity']) ?>" class="form-control" name="quantity"  placeholder="Quantité">
                                    </div>
                                </div>
                                <!-- Prix de vente -->
                                <div class="col-md-4 <?= issetor($fields['price']) ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="glyphicon glyphicon-euro"></i>
                                        </span>
                                        <input type="number" value="<?= issetor($fields['price']) ?>"class="form-control" name="price" step="any" placeholder="Prix">
                                    </div>
                                </div>
                                <!-- Statut -->
                                <div class="col-md-4 <?= issetor($fields['statut']) ?>">
                                    <select class="form-control" name="statut">
                                        <option value="">Statut</option>
                                        <option value="En stock"<?= (issetor($fields['statut'])  == 'En stock' ? 'selected' : '') ?>>En stock</option>
                                        <option value="Approvisionnement en cours"<?= (issetor($fields['statut'])  == 'Approvisionnement en cours' ? 'selected' : '') ?>>Approvisionnement en cours</option>
                                        <option value="En rupture de stock chez le fournisseur"<?= (issetor($fields['statut'])  == 'En rupture de stock chez le fournisseur' ? 'selected' : '') ?>>En rupture de stock chez le fournisseur</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-danger">Ajouter le CD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php

include ('views/partials/footer.php');
