$(function() {
    var gotoRefresh = '';
    function refreshPage(){
        // Fonction pour rafraichir la page en Ajax toutes les 5 secondes
        gotoRefresh = setInterval(function(){
            reloadPage();

        }, 5000);
    }
    refreshPage();
    function go_to_cooked(obj){
        var proco_pile = obj;
        var href = $('.cook', obj).attr('href');
        if(href){
            obj.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            var id_commande = href.substr(href.lastIndexOf('/')+1);
            $('.flash_errors').empty();
            $('.flash_success').empty();
            $.ajax({
                url: '/rest/product/order/cook.json',
                type: 'post',
                dataType: 'json',
                data: {id: id_commande},
                success: function(data) {
                    if (data.error==true) {
                        $('.flash_errors').html(data.message);
                    } else {
                        proco_pile.empty();
                        proco_pile.html(data.message);
                        var parent = proco_pile.parent();
                        $('.proco_pile_waiting', parent).remove();
                        parent.append(data.attente);
                        $('a', parent).click(function(e){
                            e.preventDefault();
                        });
                        $('.alone_products').empty();
                        $('.alone_products').html(data.alone_product);
                        $('.flash_success').html('Pile mise à jour');
                        proco_pile.removeClass('clicked');
                    }
                    refreshPage();
                },
                timeout: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                    refreshPage();
                },
                error: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                    refreshPage();
                }
            });
        }
    }
    
    function go_to_refresh(obj){
        var proco_pile = obj;
        var href = $('.cook', obj).attr('href');
        var id_commande = href.substr(href.lastIndexOf('/')+1);
        $('.flash_errors').empty();
        $('.flash_success').empty();
        $.ajax({
            url: '/rest/product/order/refresh.json',
            type: 'post',
            dataType: 'json',
            data: {id: id_commande},
            success: function(data) {
                if (data.error==true) {
                    $('.flash_errors').html(data.message);
                } else {
                    proco_pile.empty();
                    proco_pile.html(data.message);
                    var parent = proco_pile.parent();
                    $('.proco_pile_waiting', parent).remove();
                    parent.append(data.attente);
                    $('a', parent).click(function(e){
                        e.preventDefault();
                    });
                }
            },
            timeout: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            },
            error: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            }
        });
    }
    
    function go_to_refresh_unaffected(){
        $.ajax({
            url: '/rest/product/order/refresh_unaffected.json',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.error==true) {
                    $('.flash_errors').html(data.message);
                } else {
                    $('.alone_products').empty();
                    $('.alone_products').html(data.alone_product);
                    $('.flash_success').html('Refresh !');
                }
            },
            timeout: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            },
            error: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            }
        });
    }
    
    function reloadPage(){
        $('.proco_pile_top').each(function(){
            if($(this).hasClass('clicked')){
                return false;
            }else{
                go_to_refresh($(this)); 
            }
        });
        go_to_refresh_unaffected();
    }
    
    // permet de rendre toute la case qui englobe le lien cliquable
    $(".liste_poste").on('click','.proco_pile_top',function( event ) {
        clearInterval(gotoRefresh);
        if($(this).hasClass('clicked')){
            return false;
        }
        $(this).addClass('clicked');
        event.preventDefault();
        $(this).click(function(event){
            event.preventDefault();
        });
       go_to_cooked($(this));
    // Ancienne methode
    // window.location.replace($(this).children("a").attr("href"));
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
    
   $('a.cook').click(function(event) {
        event.preventDefault(event);
    });
});