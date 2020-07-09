<?php

class MessageFlash
{
    public function __construct()
    {
        if (!isset($_SESSION['flash'])) {
            $this->setFlash('');
        }
    }

    /**
     * Fonction qui cree les variables en session necessaire a l'affichage d'un message
     * @param string $message le message a afficher
     * @param string $type le type d'alerte a afficher modifie la class bootstrap alert-*
     */
    public function setFlash($message, $type = 'error')
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'alert' => $type,
        ];
    }

    /**
     * Fonction qui retourne le message contextuel contenu dans le tableau de $_SESSION['flash'] ou
     * celui retourne par la reponse JSON lors des modifications a partir du panier
     */
    public function getFlash()
    {
        if (isset($_SESSION['flash'])) {
?>
            <div class="alert alert-<?= $_SESSION['flash']['alert']; ?> text-center my-0 py-2" role="alert" id="js-error-type">
                <span class="lead" id="js-error-message"><?= $_SESSION['flash']['message']; ?></span>
            </div>
<?php
            // Suppression de la variable - elle n'existera plus des le prochain rechargement de la page
            unset($_SESSION['flash']);
        }
    }
}
