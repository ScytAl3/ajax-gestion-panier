<?php
// -- import du script de connexion a la db
require 'pdo_db_connect.php';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                      Les Fonctions produits                                          //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ----------------------------------------------------------------------
//      fonction pour renvoyer la liste des produits
// ----------------------------------------------------------------------

/**
 *  renvoie un tableau de la liste des produits
 * 
 * @return Mixed    retourne un tableau de donnees - sinon FALSE si aucun resultat pour la requete
 */
function allProductReader()
{
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete preparee 
    $queryList = "SELECT  id,
                            name,
                            image,
                            price
                    FROM `product`
                    WHERE quantity > 0
                    AND availability = 1
                    GROUP BY id
                    ORDER BY created_at DESC";
    // preparation de la requete pour execution
    try {
        $statement = $pdo->prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement->execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->rowCount()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetchAll(PDO::FETCH_OBJ);
        } else {
            // array vide
            $myReader = array();
        }
        $statement->closeCursor();
    } catch (PDOException $ex) {
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Product list...' . $ex->getMessage();
        die($msg);
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $myReader;
}

// ---------------------------------------------------------------------------
//      fonction pour renvoyer les informations d un produit
// ---------------------------------------------------------------------------

/**
 * renvoie un tableau avec les informations concernant un produit
 * 
 * @param Int   numero d identification du produit
 * 
 * @return Mixed    tableau des informations demandees, sinon FALSE
 */
function productReader($productId)
{
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete preparee 
    $queryList = "SELECT  id,
                            name,
                            quantity,
                            price,
                            image
                            FROM `product` 
                            WHERE id = :bp_productId";
    // preparation de la requete pour execution
    try {
        $statement = $pdo->prepare($queryList, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de l identifiant utilisateur
        $statement->bindParam(':bp_productId', $productId, PDO::PARAM_STR);
        // execution de la requete
        $statement->execute();
        // on verifie s il y a des resultats
        // --------------------------------------------------------
        //var_dump($statement->fetchColumn()); die; 
        // --------------------------------------------------------
        if ($statement->rowCount() > 0) {
            $myReader = $statement->fetch(PDO::FETCH_OBJ);
        } else {
            // array vide
            $myReader = array();
        }
        $statement->closeCursor();
    } catch (PDOException $ex) {
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Product detail...' . $ex->getMessage();
        die($msg);
    }
    // on retourne le resultat
    return $myReader;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                      Les Fonctions panier                                             //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ------------------------------------------------------------------------------------------
//      fonction pour creer une entree dans la table panier apres ajout d'un produit
// ------------------------------------------------------------------------------------------
/**
 * cree dans la base de donnees - table `panier` une entree associee a l identifiant de l utilisateur qui valide le panier ( ou identifaint temporaire)
 * 
 * @param Int   numero d'identification de l utilisateur
 * 
 * @return Int  numero d'identification qui vient d etre cree
 */
function createPanier($userId)
{
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO 
                                `paniers`(`panierUserTemp`, `created_at`)
                            VALUES 
                                (:bp_userId, now())";
    // preparation de la requete pour execution
    try {
        $statement = $pdo->prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage de la valeur en  parametre
        $statement->bindParam(':bp_userId', $userId, PDO::PARAM_INT);
        // execution de la requete
        $statement->execute();
        $statement->closeCursor();
        // on retourne le dernier Id cree
        return $pdo->lastInsertId();
    } catch (PDOException $ex) {
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Create Panier...' . $ex->getMessage();
        die($msg);
    }
}

// ---------------------------------------------------------------------------------------------------
//      fonction pour creer une entree dans la table ligne_commande apres validation
// ---------------------------------------------------------------------------------------------------
/**
 * cree dans la base de donnees - table `ligne_commande`les entrees associees au panier et les produits commandes
 * 
 * @param Array   numero d identification du panier, numero d identification du produit, la quantite et le prix total (facultatif)
 * 
 * @return Int   numero d identification qui vient d etre cree
 */
function createLineCmd($lineCmdData)
{
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la requete pour creer un utilisateur
    $sqlInsert = "INSERT INTO 
                                `lignes_commande`(`paniersId`, `produitsId`, `quantite`)
                            VALUES 
                                (?, ?, ?)";
    // preparation de la requete pour execution
    try {
        $statement = $pdo->prepare($sqlInsert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // execution de la requete
        $statement->execute($lineCmdData);
        $statement->closeCursor();
        // on retourne le dernier Id cree
        return $pdo->lastInsertId();
    } catch (PDOException $ex) {
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Create Command Line...' . $ex->getMessage();
        die($msg);
    }
}

// ----------------------------------------------------------------------------------------------------
//      fonction pour mettre a jour la quantite d un produit lors de la modification du panier 
// ----------------------------------------------------------------------------------------------------
/**
 * met a jour la quantite en stock d un produit qui vient d etre commande
 * 
 * @param Int   quantite commandee
 * @param Int   numero d identification du produit
 * 
 * @return String   message du deroulement de la mise a jour
 */
function updateProductQuantity($quantity, $productId)
{
    // on instancie une connexion
    $pdo = my_pdo_connexxion();
    // preparation de la  requete preparee pour mettre a jour les informations
    $sql = "UPDATE `product` SET `quantity`= (`quantity` - :bp_quantity)";
    $where = " WHERE `id`= :bp_productId";
    // construction de la requete
    $query = $sql . $where;
    // preparation de l execution de la requete
    try {
        $statement = $pdo->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // passage des valeurs en  parametre
        $statement->bindParam(':bp_quantity', $quantity, PDO::PARAM_INT);
        $statement->bindParam(':bp_productId', $productId, PDO::PARAM_INT);
        // execution de la requete
        $statement->execute();
        $statement->closeCursor();
        $msg =  "Le stock du produit a diminué de " . $quantity;
    } catch (PDOException $ex) {
        $statement = null;
        $pdo = null;
        $msg = 'ERREUR PDO Update quantity stock product...' . $ex->getMessage();
        die($msg);
    }
    $statement = null;
    $pdo = null;
    // on retourne le resultat
    return $msg;
}
