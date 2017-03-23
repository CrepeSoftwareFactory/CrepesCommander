$(function() {
    
    // permet de rendre toute la case qui englobe le lien cliquable
    $(".liste_poste").on('click','.proco_pile_top',function( event ) {
        window.location.replace($(this).children("a").attr("href"));
    });
    
    //Vérifie les commentaires de chaque pile pour savoir si il y en a ou pas et ajout d'un icone dans le code si c'est le cas
    
    
    // permet de dÃ©clencher une popup si on reste le doigts sur le bouton
    $( ".proco_pile_top" ).bind( "taphold",function( event ) {
        var href = $('.cook', this).attr('href');
        var id_commande = href.substr(href.lastIndexOf('/')+1);
        $('.modal-body').empty();
        $('.modal-body').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $.ajax({
            url: '/rest/product/order/comment.json',
            type: 'post',
            dataType: 'json',
            data: {id: id_commande},
            success: function(data) {
                var messageComment = "Pas de commentaires !";
                if (data.error) {
                    $('.flash_errors').html(data.message).show();
                } else {
                    console.log(data['comment']);
                    if(data['comment']!==null){
                         messageComment = data['comment'];
                    }
                    else{
                    }
                }
                $('.modal-body').empty();
                $('.modal-body').html(messageComment);
            }
        });
        $('input#submit').val('Valider et payer');
        $('#myModal').modal('show');
        //event.preventDefault(event);
    });
    
    $('a.cook').click(function() {
        /* a rebrancher quand l'ajax sera pret
        $.ajax({
            url: '/rest/product/order/cook.json',
            type: 'post',
            dataType: 'json',
            data: '',
            success: function(data) {
                if (data.error) {
                } else {
                }
                
                $('input#submit').val('Valider et payer');
            }
        });
        return false;
        */
    });
});