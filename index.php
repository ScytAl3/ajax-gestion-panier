<?php
// Import du script pdo des fonctions qui accedent a la base de donnees
require __DIR__ . '/pdo/pdo_db_functions.php';
// Import et instanciation des class Panier & Message
require __DIR__ . '/class/panier.class.php';
require __DIR__ . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();
//----------------------------//----------------------------
//                              USER
// Verification de  l'existence de la variable userId, sinon on la cree
if (!isset($_SESSION['userId'])) {
    // Initialisation de l'identifiant temporaire de l'utilisateur
    $_SESSION['userId'] = time();
}
//                              USER
//----------------------------//----------------------------
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- default Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion panier - Liste des produits</title>
    <meta name="author" content="Franck Jakubowski">
    <meta name="description" content="Mettre à jour un panier à l'aide d'Ajax.">
    <!--  favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- bootstrap stylesheet -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- font awesome stylesheet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js" integrity="sha512-M+hXwltZ3+0nFQJiVke7pqXY7VdtWW2jVG31zrml+eteTP7im25FdwtLhIBTWkaHRQyPrhO2uy8glLMHZzhFog==" crossorigin="anonymous"></script>
    <!-- current page stylesheet -->
    <link href="css/index.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Import du header -->
    <?php include 'layouts/partials/_header.php'; ?>
    <!-- /Import du header -->

    <!-- Zone pour afficher un message flash -->
    <?php $flash = $messageFlash->getFlash(); ?>
    <!-- /Zone pour afficher un message flash -->

    <div class="container mt-5">
        <!-- Titre de la page de presentation des produits -->
        <div class="">
            <div class="text-center mx-auto">
                <!-- Message d'information pour tester la connexion a la base de donnees -->
                <div class="alert alert-info" role="alert">
                    <?php require 'pdo/pdo_db_connect.php';
                    // Instanciation d'une connexion avec la base de donnees pour verifie s il n y a pas d'erreurs
                    // avec les parametres de connexion
                    $pdo = my_pdo_connexxion();
                    if ($pdo) {
                        echo 'Connexion réussie à la base de données';
                    } else {
                        var_dump($pdo);
                    }
                    ?>
                </div>
                <!-- /Message d information pour tester la connexion a la base de donnees -->

                <h2 class="display-4 font-weight-bold">Liste des produits disponibles</h2>

            </div>
        </div>
        <!-- /Titre de la page de presentation des produits -->

        <div class="row">
            <!-- Recuperation de tous les produits -->
            <?php
            /*
                Appelle de la fonction qui retourne les information de chaque produits
            */
            $products = allProductReader();

            // var_dump($products); die;

            // Si: la fonction retourne un tableau
            if (!empty($products)) {
                // Boucle dans le tableau retourne pour afficher les differentes produits
                foreach ($products as $product) {
            ?>
                    <!-- Container pour afficher la liste des produits -->
                    <div class=" col-12 col-sm-6 col-md-4 mt-4">
                        <div class="border border-dark rounded p-2 clearfix">
                            <h4 class="product-name text-muted text-truncate">
                                <strong>
                                    <?= $product->name; ?>
                                </strong>
                            </h4>
                            <div class="text-right">
                                <span class="badge badge-info product-price my-1">
                                    <?= $product->price; ?> €
                                </span>
                            </div>
                            <img class="img-fluid rounded mx-auto d-block" src=<?= $product->image; ?> alt="Image de <?= $product->name; ?>">
                            <hr>
                            <a class="btn btn-success float-right" href="php_process/cart_add.php?id=<?= $product->id; ?>">
                                <i class="fas fa-cart-plus mr-1"></i>
                                Ajouter
                            </a>
                        </div>

                    </div>
                    <!-- /Container pour afficher la liste des produits -->

                <?php
                }
                ?>
        </div>
    <?php
                // Sinon: la fonction a retourne un tableau vide
            } else {
    ?>
        <!-- Affiche un message pour dire qu'il n'y a pas encore de produits dans la base de donnees -->
        <div class="my-3">
            <div class="mx-auto px-3 py-2 text-center info-message-bg">
                <h2 class="card-title">Il n'y a aucun produit à afficher pour le moment !</h2>
            </div>
        </div>
        <!-- /Affiche un message pour dire qu'il n'y a pas encore de produits dans la base de donnees -->
    <?php
            }
    ?>
    </div>
    <!-- Import du footer -->
    <?php include 'layouts/partials/_footer.php'; ?>
    <!-- /Import du footer -->

    <!------------------------------------------>
    <?= var_dump($_SESSION); ?>
    <!------------------------------------------>

    <!-- Import scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!-- /Import scripts -->
</body>

</html>