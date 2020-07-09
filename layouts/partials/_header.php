<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">MaBoutique</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor03">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Produits</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <!-- affiche un lien vers le panier avec le nombre de produits ajoutes -->
                    <a class="btn btn-outline-success my-2 my-sm-0" href="panier.php">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <strong class="<?= ($panier->quantite_totale_panier() > 0) ? '' : 'd-none';?>">
                            <span class="badge badge-light badge-pill" id="js-panier-count"><?= $panier->quantite_totale_panier(); ?></span>
                        </strong>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- /navbar -->