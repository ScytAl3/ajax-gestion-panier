<?php

class Panier
{
    public function __construct()
    {
        // Demarre la $_SESSION si elle n'existe pas
        if (!isset($_SESSION)) {
            session_start();
        }

        // Creation du panier s'il n'existe pas
        if (!isset($_SESSION['panier'])) {
            // initialisation du panier 
            $_SESSION['panier'] = array();
            // subdivision du panier 
            $_SESSION['panier']['id_product'] = array();
            $_SESSION['panier']['qte_product'] = array();
            $_SESSION['panier']['prix'] = array();
        }        
    }

    // ------------------------------------------------------------------------------
    // FONCTION : verification presence d un produit dans le panier
    // ------------------------------------------------------------------------------

    /**
     * Verifie la presence d un produit dans le panier
     *
     * @param String $id_product numéro d identification du produit a verifier
     * @return Boolean Renvoie TRUE si le produit est trouve dans le panier, FALSE sinon
     */
    public function alreadyExist($product_id)
    {
        // on initialise la variable de retour
        $find = false;
        // on verifie les numeros d identification des produit et on compare avec celui du produit a verifier
        if (count($_SESSION['panier']['id_product']) > 0 && array_search($product_id, $_SESSION['panier']['id_product']) !== false) {
            $find = true;
        }
        return $find;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : ajout d un produit dans le panier
    // ------------------------------------------------------------------------------

    /**
     * Ajoute le produit selectionne dans le panier
     * 
     * @param Int $product_id: identifiant du produit
     * @param Int $product_qty: quantite du produit (1 par defaut)
     * @param Int $product_price: prix du produit
     * 
     */
    public function add($product_id, $product_qty, $product_price)
    {
        // on ajoute l identifiant du produit dans le tableau du panier
        array_push($_SESSION['panier']['id_product'], $product_id);
        // on ajoute la quantite par defau du produit dans le tableau du panier
        array_push($_SESSION['panier']['qte_product'], $product_qty);
        // on ajoute le prix unitaire du produit dans le tableau du panier
        array_push($_SESSION['panier']['prix'], $product_price);
    }

    // ------------------------------------------------------------------------------
    // FONCTION : suppression d un produit dans le panier
    // ------------------------------------------------------------------------------

    /**
     * supprimer un article du panier
     * 
     * @param Int       $id_product numero d identification du produit a supprimer 
     * @param Boolean   $reindex : facultatif, par defaut, vaut true pour re-indexer le tableau apres 
     *                                  suppression. On peut envoyer false si cette re-indexation n est pas necessaire. 
     * 
     * @return Mixed    retourne TRUE si la suppression a bien ete effectuee - FALSE sinon - "absent" si 
     *                                  le produit a deja ete retire du panier 
     */
    public function deleteProduct($id_product, $reindex = true)
    {
        $suppression = false;
        // Cherche dans le sous-tableau des ids produit la cle (position) qui correspond a l'id du produit
        // que l'on veut supprimer
        $keyDelete = array_keys($_SESSION['panier']['id_product'], $id_product);
        // Si la posistion de la cle a ete trouvee
        if (!empty($keyDelete)) {
            // on parcours les sous-tableaux du panier pour supprimer le produit: id_product[],
            // qte_product[] et prix[]
            foreach ($_SESSION['panier'] as $key => $value) {

                foreach ($keyDelete as $position) {
                    // Dans chaque sous-tableaux on detruit les variables de session associees au produit
                    // a supprimer
                    unset($_SESSION['panier'][$key][$position]);
                }
                // reindexation des tableaux
                if ($reindex == true) {
                    $_SESSION['panier'][$key] = array_values($_SESSION['panier'][$key]);
                }
                $suppression = true;
            }
        } else {
            $suppression = "absent";
        }
        return $suppression;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : renvoie la quantite d un produit dans le panier
    // ------------------------------------------------------------------------------

    /**
     * modifie la quantite d un article dans le panier
     *
     * @param Int $id_product identifiant du produit a modifier
     * 
     * @return Boolean  retourne VRAI si la modification a bien eu lieu, FAUX sinon.
     */
    public function product_quantity($id_product)
    {
        // on compte le nombre de produits differents dans le panier 
        $nb_produit = count($_SESSION['panier']['id_product']);
        // on initialise la variable de retour
        $qte = 0;
        // on parcoure le tableau de session pour recuperer le produit precis
        for ($i = 0; $i < $nb_produit; $i++) {
            if ($id_product == $_SESSION['panier']['id_product'][$i]) {
                $qte = $_SESSION['panier']['qte_product'][$i];
            }
        }
        return $qte;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : modifier la quantite d un produit dans le panier
    // ------------------------------------------------------------------------------

    /**
     * modifie la quantite d un article dans le panier
     *
     * @param Int $id_product identifiant du produit a modifier
     * @param String   $operation ajoute ou retire 1 a la quantite
     * 
     * @return Boolean  retourne VRAI si la modification a bien eu lieu, FAUX sinon.
     */
    public function modif_qte($id_product, $operation)
    {
        // on compte le nombre de produits differents dans le panier 
        $nb_produit = count($_SESSION['panier']['id_product']);
        // on initialise la variable de retour
        $modification = false;
        // on parcoure le tableau de session pour modifier le produit precis
        for ($i = 0; $i < $nb_produit; $i++) {
            if ($id_product == $_SESSION['panier']['id_product'][$i]) {
                $qte = ($operation == "down") ? -1 : 1;
                $_SESSION['panier']['qte_product'][$i] = $_SESSION['panier']['qte_product'][$i] + $qte;
                if ($_SESSION['panier']['qte_product'][$i] < 1) {
                    $_SESSION['panier']['qte_product'][$i] = 1;
                }
                $modification = true;
            }
        }
        return $modification;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : calculer le total des produits differents
    // ------------------------------------------------------------------------------

    /**
     * calcule la quantite totale des produits du panier
     *
     * @return Int
     */
    public function quantite_totale_panier()
    {
        // on initialise le montant 
        $quantite = 0;
        // comptage des produits du panier
        $nb_produits = count($_SESSION['panier']['id_product']);
        // on calcule le total par produit
        for ($i = 0; $i < $nb_produits; $i++) {
            $quantite += $_SESSION['panier']['qte_product'][$i];
        }
        // on retourne le resultat
        return $quantite;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : calculer le total de la quantite d'un produit
    // ------------------------------------------------------------------------------

    /**
     * calcule le montant total du panier
     * 
     * @param Int $id_product: identifiant du produit
     *
     * @return Double
     */
    public function montant_total_produit($id_product)
    {
        // on initialise le montant 
        $montant = 0;
        // Cherche dans le sous-tableau des ids produit la cle (position) qui correspond a l'id du produit
        // que l'on veut supprimer
        $key = array_keys($_SESSION['panier']['id_product'], $id_product);
        // on calcule le total de la quantite du produit
        $montant = $_SESSION['panier']['qte_product'][$key[0]] * $_SESSION['panier']['prix'][$key[0]];
        // on retourne le resultat
        return $montant;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : calculer le total du panier
    // ------------------------------------------------------------------------------

    /**
     * calcule le montant total du panier
     *
     * @return Double
     */
    public function montant_panier()
    {
        // on initialise le montant 
        $montant = 0;
        // comptage des produits du panier
        $nb_produits = count($_SESSION['panier']['id_product']);
        // on calcule le total par produit
        for ($i = 0; $i < $nb_produits; $i++) {
            $montant += $_SESSION['panier']['qte_product'][$i] * $_SESSION['panier']['prix'][$i];
        }
        // on retourne le resultat
        return $montant;
    }

    // ------------------------------------------------------------------------------
    // FONCTION : vider le panier
    // ------------------------------------------------------------------------------

    /**
     * vide le panier en cours
     * 
     * @return Boolean  retourne TRUE si le panier est vide, sinon FALSE
     */
    public function vider_panier()
    {
        $vide = false;
        unset($_SESSION['panier']);
        if (!isset($_SESSION['panier'])) {
            $vide = true;
        }
        return $vide;
    }
}
