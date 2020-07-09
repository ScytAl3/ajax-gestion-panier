<?php
// Import pdo fonction sur la database
require dirname(__DIR__) . '/pdo/pdo_db_functions.php';
// import et instanciation des class Panier & Message
require dirname(__DIR__) . '/class/panier.class.php';
require dirname(__DIR__) . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();

if (isset($_GET['id'])) {
    /*
        Verification que le produit existe
    */
    $product = productReader($_GET['id']);
    // Si le tableau retourne est vide
    if (empty($product)) {
        // Appel de la methode qui set le message - erreur
        $type = 'danger';
        $message = "Ce produit n'existe pas";
        $messageFlash->setFlash($message, $type);
        // Redirection vers la page de la liste des produits
        header('location:'.dirname(__DIR__).'/index.php');
        die();
    }

    /*
        Verification que le produit n'est pas deja dans le panier
    */
    $checkProduct = $panier->alreadyExist($product->id);

    // Si le produit existe dans le panier
    if ($checkProduct) {
        // Ajoute 1 a la quantite
        $productNewQuantity = $panier->modif_qte($product->id, $direction);

        // Si la quantite a bien ete modifiee
        if ($productNewQuantity) {
            // Appel de la methode qui set le message en session - success
            $type = 'success';
            $message = "Le produit " . $product->name . " a été augmenté de 1";
            $messageFlash->setFlash($message, $type);
            // Redirection vers la page de la liste des produits
            header('location:../index.php');
            die();

        } else {
            // Sinon appel de la methode qui set le message - erreur
            $type = 'danger';
            $$message = "Le produit " . $product->name . " n'a pas été augmenté de 1";
            $messageFlash->setFlash($message, $type);
            // Redirection vers la page de la liste des produits
            header('location:../index.php');
            die();
        }

    } else {
        // Sinon ajoute le nouveau produit dans le panier
        $addProduct = $panier->add($product->id, 1, $product->price);
        // Redirection vers la page du panier
        header('location:../panier.php');
        die();
    }
}
