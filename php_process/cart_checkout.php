<?php
// Import pdo fonction sur la database
require dirname(__DIR__) . '/pdo/pdo_db_functions.php';
// import et instanciation des class Panier & Message
require dirname(__DIR__) . '/class/panier.class.php';
require dirname(__DIR__) . '/class/messageFlash.class.php';
$panier = new Panier();
$messageFlash = new MessageFlash();

// Recuperation de l identifiant de l utilisateur
$userCartId = $_SESSION['userId'];
// Appel de la methode de Creation du panier dans la table - retourne l'identifiant cree
$newCartId = createPanier($userCartId);
/* 
    Si  newCartId = FALSE
*/
if ($newCartId < 0) {
    // Appel de la methode qui set le message - erreur
    $type = 'danger';
    $message = "Problème lors de la création du panier !";
    $messageFlash->setFlash($message, $type);
    // Redirection vers la page du panier
    header('location:../panier.php');
    die();
}
//  -----------------------------------------------//---------------------------------------------------
//                     Creation des ligne_commande associees au panier et aux produits
//                          mise a jour de la quantite en stock des produits du panier
//  ----------------------------------------------------------------------------------------------------
// Compte le nombre de produits differents dans le panier 
$nb_produit = count($_SESSION['panier']['id_product']);
// Parcours a chaque iteration les sous tableaux du panier
for ($i = 0; $i < $nb_produit; $i++) {
    // Recuperation de l'identifiant du produit
    $productId = $_SESSION['panier']['id_product'][$i];
    // Recuperation de la quantite de ce produit
    $productQuantity = $_SESSION['panier']['qte_product'][$i];
    // Preparation du tableau des donnees a passer en parametre a la fonction qui va inserer les lignes dans la table
    $lineCmdData = [
        $newCartId,         // identifiant du panier cree 
        $productId,         // identifiant du produit
        $productQuantity    // le total de la ligne de commande
    ];
    //  ----------------------------------------------------------------------------------------------------
    //                              Creation de la ligne a chaque iteration
    //  ----------------------------------------------------------------------------------------------------
    $newLineCmd = createLineCmd($lineCmdData);
    //  ------------------------------------------------------------------------------------------------------
    //            Mise a jour du stock des produits contenu dans le panier a chaque iteration
    //  ------------------------------------------------------------------------------------------------------
    $updateQteProduct = updateProductQuantity($productQuantity, $productId);
}
//  ------------------------------------------------------------------------------------------------------
//                     Creation des ligne_commande associees au panier et aux produits
//                          mise a jour de la quantite en stock des produits du panier
//  -----------------------------------------------//-----------------------------------------------------
// Appel de la methode qui vide le panier
$panier->vider_panier();
// Appel de la methode qui set le message - success
$type = 'success';
$message = "Votre panier a été crée !";
$messageFlash->setFlash($message, $type);
// Redirection vers la page index
header('location:../index.php');
