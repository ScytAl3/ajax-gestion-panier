<?php
// Import du script pdo des fonctions qui accedent a la base de donnees
require __DIR__ . '/pdo/pdo_db_functions.php';
// Import et instanciation des class Panier & Message
require __DIR__ . '/class/panier.class.php';
require __DIR__ . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();
// verification que l utilisateur ne passe pas par l URL si le panier est vide
if (isset($_SESSION['panier']) && empty($_SESSION['panier']['id_product'])) {
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- default Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion panier - Panier en cours</title>
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
    <link href="css/panier.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Import du header -->
    <?php include 'layouts/partials/_header.php'; ?>
    <!-- /Import du header -->

    <!-- Zone pour afficher un message flash -->
    <?php $flash = $messageFlash->getFlash(); ?>
    <!-- /Zone pour afficher un message flash -->

    <!-- Container de la page du panier -->
    <div class="mt-5 container">

        <!-- Bouton pour continuer ses achats -->
        <div class="my-3 row">
            <a class="btn btn-success" href="index.php">Continuer mes achats</a>
        </div>
        <!-- /Bouton pour continuer ses achats -->

        <div class="my-3 py-3">
            <div class="text-center mx-auto">
                <!-- titre de la page de presentation des produits -->
                <h2 class="display-4 font-weight-bold">Votre panier</h2>
            </div>
        </div>

        <?php
        // Recupere les ids des differents produits dans le panier 
        $ids = array_values($_SESSION['panier']['id_product']);
        
        // var_dump($ids);die;
        
        /*
            Section pour afficher les produits du panier 
        */
        // Parcours du tableau des ids du panier pour recuperer les informations associees a ce produit
        // a chaque iteration
        foreach ($ids as $id) {
            // Appel de la fonction qui renvoie les informations du produit
            $product = productReader($id);

            // Calcul du prix total du produit en fonction de la quantite
            $productTotalPrice =  $product->price * $panier->product_quantity($id);
        ?>
            <!-- Produit <?= $product->name; ?> -->
            <div class="row card-header text-white bg-info mb-3">
                <!-- Photo produit -->
                <div class="mx-auto">
                    <img class="min-picture" src="<?= $product->image; ?>" alt="Photo du produit">
                </div>
                <!-- /Photo produit -->

                <!-- Nom produit -->
                <div class="col-md align-self-center text-center">
                    <h2 class="card-title"><strong><?= $product->name; ?></strong></h2>
                </div>
                <!-- /Nom produit -->

                <!-- Selection quantite -->
                <div class="col-md align-self-center">

                    <!-- Boutons pour modifier la quantite d un produit du panier -->
                    <div class="quantity-modifier text-center js-quantity-modifier">

                        <a class="quantity-down" href="#" data-direction="down"><i class="fas fa-minus-square"></i></a>

                        <!-- Passage de data pour le traitement de la quantite et le message de modification-->
                        <span class="mx-2" id="js-quantity-product-<?= $product->id ?>" data-itemid="<?= $product->id; ?>" data-itemname="<?= $product->name; ?>"><?= $panier->product_quantity($id); ?></span>

                        <a class="quantity-up" href="#" data-direction="up"><i class="fas fa-plus-circle"></i></a>

                    </div>
                    <!-- /Boutons pour modifier la quantite d un produit du panier -->

                </div>
                <!-- /Selection quantite -->

                <!-- Prix du produit -->
                <div class="col-md align-self-center mx-auto">
                    <h2 class="text-center" id="js-montant-product-<?= $product->id ?>"><?= number_format($panier->montant_total_produit($id), 2, ',', ' '); ?> €</h2>
                </div>
                <!-- /Prix du produit -->

                <!-- Boutons pour supprimer un produit du panier -->
                <div class="align-self-center mx-auto">
                    <a class="btn btn-danger" href="php_process/cart_delete.php?id=<?= $product->id; ?>"><i class="fas fa-trash" aria-hidden="true"></i></a>
                </div>
                <!-- /Boutons pour supprimer un produit du panier -->
            </div>
        <?php
        }
        /*
            /Section pour afficher les produits du panier 
        */
        ?>

        <!-- Section pour afficher le montant total du panier dynamiquement -->
        <div class="row mb-3">
            <div class="card ml-auto" style="width: 18rem;">
                <div class="card-header">Nombre de produit :
                </div>
                <div class="card-body text-right">
                    <p class="card-text" id="js-panier-quantite"><?= $panier->quantite_totale_panier(); ?></p>
                </div>
                <div class="card-header">Total du panier :
                </div>
                <div class="card-body text-right">
                    <p class="card-text" id="js-panier-montant">€ <?= number_format($panier->montant_panier(), 2, ',', ' '); ?></p>
                </div>
            </div>
        </div>
        <!-- /Section pour afficher le montant total du panier dynamiquement -->

        <!-- Bouton pour valider le panier -->
        <div class="row">
            <a type="submit" class="btn btn-success btn-lg btn-block" href="php_process/cart_checkout.php">Valider panier</a>
        </div>
        <!-- /Bouton pour valider le panier -->

    </div>
    <!-- /Container de la page du panier -->

    <!-- Import du footer -->
    <?php include 'layouts/partials/_footer.php'; ?>
    <!-- /Import du footer -->

    <!------------------------------------------>
    <?= var_dump($_SESSION) ?>
    <!------------------------------------------>

    <!-- Import scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="js/cart.js"></script>
    <!-- /Import scripts -->
</body>

</html>