<?php
// Import pdo fonction sur la database
require dirname(__DIR__) . '/pdo/pdo_db_functions.php';
// import et instanciation des class Panier & Message
require dirname(__DIR__) . '/class/panier.class.php';
require dirname(__DIR__) . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();

// initialisation d'un tableau json pour la reponse ajax
$json = array('error' => 'danger',);

if (isset($_POST['id'], $_POST['name'], $_POST['direction'])) {
    // Stock l'information de l'operation +/-
    $operation = ($_POST['direction'] === "up") ? 'augmentée' : 'diminuée';
    // Appel la fonction qui modifie la quantite du produit en fonction de la direction
    $newQuantity = $panier->modif_qte($_POST['id'], $_POST['direction']);
    // Si l'operation c'est bien deroulee
    if ($newQuantity) {
        // Message de succes
        $json['error'] = 'success';
        $json['message'] = "La quantité de " . $_POST['name'] . " a " . $operation . " de 1";
        // modification d'un produit
        $json['productNewQuantity'] = $panier->product_quantity($_POST['id']);
        $json['productNewTotal'] = number_format($panier->montant_total_produit($_POST['id']), 2, ',', ' ');
        // montant et quantite totales
        $json['panierNewProductQuantity'] = $panier->quantite_totale_panier();
        $json['panierNewMontant'] = number_format($panier->montant_panier(), 2, ',', ' ');
    } else {
        // Message d'erreur
        $json['message'] = "La quantité du produit " . $_POST['name'] . " n'a pas pu être modifiée";
    }
    // Encodage de la reponse au format JSON
    echo json_encode($json);
}
