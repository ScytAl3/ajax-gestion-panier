$(document).ready(function () {
    var $container = $('.js-quantity-modifier');

    console.log($container);

    // On cherche l'evenement se produit sur un un tag <a> contenu dans la <div> 
    // dont l'id="js-quantity-modifier"
    $container.find('a').on('click', function (e) {
        e.preventDefault();
        
        // Recupere l'element en cours de clic
        var $operation = $(e.currentTarget);
        // Recupere l'identifiant du produit en fonction de la position du "currentTarget"
        var $productid = ($operation.data('direction') === 'down') ? $operation.next().data('itemid') : $operation.prev().data('itemid');
        // Recupere le nom du produit en fonction de la position du "currentTarget"
        var $productname = ($operation.data('direction') === 'down') ? $operation.next().data('itemname') : $operation.prev().data('itemname');

        $.ajax({
            url: './php_process/cart_quantity.php',
            type: 'POST',
            data: {
                "direction": $operation.data('direction'),
                "id": $productid,
                "name": $productname
            },
            dataType: "json",
            success: function (data) {
                console.log(data);

                // mise a jour du message de modification
                $("#js-error-type").removeClass("alert-error").addClass("alert-" + data.error);
                $("#js-error-message").text(data.message);

                // mise a jour du compteurpanier du header
                $("#js-panier-count").text(data.panierNewProductQuantity);

                // mise a jour du produit modifie
                $("#js-quantity-product-" + $productid).text(data.productNewQuantity);
                $("#js-montant-product-" + $productid).text(data.productNewTotal + ' €');

                // mise a jour de la quantite et du montant total du panier
                $("#js-panier-quantite").text(data.panierNewProductQuantity);
                $("#js-panier-montant").text('€ ' + data.panierNewMontant);
            }
        });
    });
});