<?php
// Import pdo fonction sur la database
require dirname(__DIR__) . '/pdo/pdo_db_functions.php';
// import et instanciation des class Panier & Message
require dirname(__DIR__) . '/class/panier.class.php';
require dirname(__DIR__) . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();

if (isset($_GET['id'])) {
    // Appel de la methode de suppression d'un produit du panier
    $productDeleted = $panier->deleteProduct($_GET['id']);

    // Si la suppression c'est bien deroulee: retourne un booleen
    if (!is_string($productDeleted)) {
        // Appel de la methode qui set le message en session - success
        $type = 'success';
        $message = "Le produit " . $product->name . " a bien été supprimé votre panier !";
        $messageFlash->setFlash($message, $type);
        // Redirection vers la page du panier des produits
        header('location:../panier.php');
        die();
        // sinon retourne 'absent'
    } else {
        // Appel de la methode qui set le message en session - error
        $type = 'danger';
        $message = "Le produit " . $product->name . " n'est pas dans votre panier";
        $messageFlash->setFlash($message, $type);
        // Redirection vers la page du panier des produits
        header('location:../panier.php');
        die();
    }
}
