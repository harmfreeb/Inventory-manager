<?php
require_once('includes/load.php')
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title><?= $title_page ?></title>
        <meta name="description" content="A simple starter template for front-end projects." />

        <!-- Css import -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
        <link href="<?= URL ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>
    <body>
    <header id="header">
        <a href="/"><div class="logo pull-left"><img src="<?= URL ?>assets/img/logo.ico" alt=""><span>Disquaire, Paris 9</span> </div></a>
        <div class="header-content">
            <div class="header-date pull-right">
                <strong><?= date("d/m/Y, G:i ") ?></strong>
            </div>
        </div>
    </header>


    <div class="container-fluid">
