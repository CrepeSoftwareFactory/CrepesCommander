$(function() {
    var gotoRefresh = '';
//    function refreshPage(){
//        // Fonction pour rafraichir la page en Ajax toutes les 5 secondes
//        gotoRefresh = setInterval(function(){
//            reloadPage();
//        }, 5000);
//    }
//    refreshPage();
    
    function reloadPage(){
        var hadToRefresh = $('.hadToRefresh').val();
        if(hadToRefresh == 0){
            go_to_refresh();
            go_to_refresh_unaffected();
        }
    }
    
    function verif_no_proco_pile(){
        $('.proco_pile_top').each(function(){
            var nb_proco = 0;
            var parent = $(this).parent('ul');
            nb_proco = $('.proco_pile_waiting', parent).length;
            console.log(nb_proco);
            if(nb_proco == 0){
                parent.append('<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>');
            }
        });
    }
    
    function go_to_cooked(obj){
        var proco_pile = obj;
        var href = $('.cook', obj).attr('href');
        var product_before = proco_pile.attr('id');
        if(href){
            var contenuObj = obj.html();
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
                        proco_pile.html(contenuObj);
                    } else {
                        $('#'+data.idProduct).remove();
                        proco_pile.html();
                        proco_pile.html(data.message);
                        var parent = proco_pile.parent();
                        proco_pile.attr('id', data.idProduct);
                        $('.flash_success').html('Pile mise à jour');
                    }
                    proco_pile.removeClass('clicked');
                    $('.hadToRefresh').val(0);
                },
                timeout: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                    proco_pile.removeClass('clicked');
                    $('.hadToRefresh').val(0);
                },
                error: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                    proco_pile.removeClass('clicked');
                    $('.hadToRefresh').val(0);
                }
            });
        }
    }
    
    function go_to_refresh(){
        $('.proco_pile_top').each(function(){
            var proco_pile = $(this);
            var href = $('.cook', this).attr('href');
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
                        var parent = proco_pile.parent();
                        parent.empty();
                        parent.html('<li class="btn btn-default btn-lg btn-block proco_pile_top" id="'+data.idProduct+'"></li>')
                        $('li', parent).html(data.message);
                        $('.proco_pile_waiting', parent).remove();
                        parent.append(data.attente);
                        $('a', parent).click(function(e){
                            e.preventDefault();
                        });
                        refresh_procopile_top($('li', parent));
                    }
                },
                timeout: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                },
                error: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                }
            });
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
                    console.log('IN');
                }
                refresh_proco();
            },
            timeout: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            },
            error: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
            }
        });
    }
    
    refresh_proco();
    go_to_refresh();
    //fonction bind procopile
    function refresh_procopile_top(obj){
        obj.bind( "taphold",function( event ) {
            event.stopPropagation();
            event.preventDefault();
            $('.hadToRefresh').val(1);
            var obj = $(this);
            if(!obj.hasClass('proco_pile_top')){
                obj.css('background-color', 'cadetblue');
            }
            var id = $(this).attr("id");
            $('.modal-body').empty();
            $('.modif_status .dropdown-toggle').attr('data-status', '');
            $('.modif_status .dropdown-toggle').attr('data-idproduct', '');
            $('.modif_status .dropdown-toggle').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modif_pile .dropdown-toggle').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modal-header').empty();
            $('.modal-header').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modal-body').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modif_status .dropdown-toggle').addClass('disabled', true);
            $('.modif_pile .dropdown-toggle').addClass('disabled', true);
            $.ajax({
                url: '/rest/product/order/status.json',
                type: 'post',
                dataType: 'json',
                data: {id: id},
                success: function(data) {
                    var messageComment = "Pas de commentaires !";
                    if (data.error) {
                        $('.flash_errors').html(data.message).show();
                    } else {
                        if(data['comment']!==null){
                             messageComment = data['comment'];
                        }
                        $('.modal-body').empty();
                        $('.modal-body').html(messageComment);
                        $('.modif_status .dropdown-toggle').attr('data-status', data.message.proco_status_id);
                        if(data.pile=="PILE"){
                             $('.modif_pile .dropdown-toggle').attr('data-pile', 0);
                            $('.modif_pile .dropdown-toggle').html(data.pile+' <span class="caret"></span>');
                        }
                        else{
                            $('.modif_pile .dropdown-toggle').attr('data-pile', data.pile.station_id);
                            $('.modif_pile .dropdown-toggle').html(data.pile.name+' <span class="caret"></span>');
                        }
                        $('.linkStatus').attr('data-idproduct', id);
                        $('.modif_status .dropdown-toggle').html(data.message.name+' <span class="caret"></span>');
                        $('.modal-header').empty();
                        $('.modal-header').append(data.title);
                    }
                    $('.modif_pile .dropdown-toggle').removeClass('disabled');
                    $('.modif_status .dropdown-toggle').removeClass('disabled');
                    
                    obj.css('background-color', '');
                    $('.hadToRefresh').val(0);
                    
                }
            });
            $('#myModal').modal('show');
        });
    }
    
    //Bind de fonction sur le clic des listes des crêpes unaffected
    function refresh_proco(){
        $(".colonne_pile li, .proco_pile_waiting").bind( "taphold",function( event ) {
            event.stopPropagation();
            event.preventDefault();
            $('.hadToRefresh').val(1);
            var obj = $(this);
            if(!obj.hasClass('proco_pile_top')){
                obj.css('background-color', 'cadetblue');
            }
            var id = $(this).attr("id");
            $('.modal-body').empty();
            $('.modif_status .dropdown-toggle').attr('data-status', '');
            $('.modif_status .dropdown-toggle').attr('data-idproduct', '');
            $('.modif_status .dropdown-toggle').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modif_pile .dropdown-toggle').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modal-header').empty();
            $('.modal-header').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modal-body').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('.modif_status .dropdown-toggle').addClass('disabled', true);
            $('.modif_pile .dropdown-toggle').addClass('disabled', true);
            $.ajax({
                url: '/rest/product/order/status.json',
                type: 'post',
                dataType: 'json',
                data: {id: id},
                success: function(data) {
                    var messageComment = "Pas de commentaires !";
                    if (data.error) {
                        $('.flash_errors').html(data.message).show();
                    } else {
                        if(data['comment']!==null){
                             messageComment = data['comment'];
                        }
                        $('.modal-body').empty();
                        $('.modal-body').html(messageComment);
                        $('.modif_status .dropdown-toggle').attr('data-status', data.message.proco_status_id);
                        if(data.pile=="PILE"){
                            $('.modif_pile .dropdown-toggle').attr('data-pile', 0);
                            $('.modif_pile .dropdown-toggle').html(data.pile+' <span class="caret"></span>');
                        }
                        else{
                            $('.modif_pile .dropdown-toggle').attr('data-pile', data.pile.station_id);
                            $('.modif_pile .dropdown-toggle').html(data.pile.name+' <span class="caret"></span>');
                        }
                        $('.linkStatus').attr('data-idproduct', id);
                        $('.modif_status .dropdown-toggle').html(data.message.name+' <span class="caret"></span>');
                        $('.modal-header').empty();
                        $('.modal-header').append(data.title);
                    }
                    $('.modif_pile .dropdown-toggle').removeClass('disabled');
                    $('.modif_status .dropdown-toggle').removeClass('disabled');
                    
                    obj.css('background-color', '');
                    $('.hadToRefresh').val(0);
                    
                }
            });
            $('#myModal').modal('show');
        });
    }
    
    maj_status();
    //Fonction de maj de status d'un proco
    function maj_status(){
        //Fonction avec Ajax pour modifier la pile d'une proco depuis la modale
        $('.modif_pile li').on('click', function(){
            $('.hadToRefresh').val(1);
            var newPile = $('a', this).attr('data-pile');
            var idproduct = $('a', this).attr('data-idproduct');
            var oldPile = $(this).parent('ul').prev('button').attr('data-pile');
            if( newPile !== oldPile ){
                var obj = $(this);
                obj.parent('ul').prev('button').addClass('disabled');
                obj.parent('ul').prev('button').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                $.ajax({
                    url: '/rest/product/order/change_pile.json',
                    type: 'post',
                    dataType: 'json',
                    data: {idProduct: idproduct, newPile: newPile},
                    success: function(data){
                        if (data.error==true) {
                            $('.flash_errors').html(data.message);
                        } else {
                            $('.flash_success').html(data.message);
                            if(data.newPile=="PILE" || data.newPile==null){
                                obj.parent('ul').prev('button').html('PILE <span class="caret"></span>');
                                obj.parent('ul').prev('button').attr('data-pile', 0);
                                $('#'+idproduct).remove();
                                verif_no_proco_pile();
                                go_to_refresh_unaffected();
                            }
                            else{
                                obj.parent('ul').prev('button').html(data.newPile.name+' <span class="caret"></span>');
                                obj.parent('ul').prev('button').attr('data-pile', data.newPile.station_id);
                                $('#'+idproduct).remove();
                                verif_no_proco_pile();
                                go_to_refresh();
                            }
                        }
                        obj.parent('ul').prev('button').removeClass('disabled');
                        $('.hadToRefresh').val(0);
                    },
                    error: function() {
                        $('.flash_errors').html('Impossible de joindre le serveur !!!');
                        obj.parent('ul').prev('button').removeClass('disabled');
                        $('.hadToRefresh').val(0);
                    }
                });
            }
        });
        //Fonction avec requête ajax pour modifier le status de priorité d'une proco depuis la modale
        $('.modif_status li').on('click', function(){
            $('.hadToRefresh').val(1);
            var newStatus = $('a', this).attr('data-status').toString();
            var idproduct = $('a', this).attr('data-idproduct');
            var oldStatus = $(this).parent('ul').prev('button').attr('data-status').toString();
            if( newStatus !== oldStatus ){
                var obj = $(this);
                obj.parent('ul').prev('button').addClass('disabled');
                obj.parent('ul').prev('button').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                $.ajax({
                    url: '/rest/product/order/change_status.json',
                    type: 'post',
                    dataType: 'json',
                    data: {idProduct: idproduct, newStatus: newStatus},
                    success: function(data){
                        if (data.error==true) {
                            $('.flash_errors').html(data.message);
                        } else {
                            $('.flash_success').html(data.message);
                            obj.parent('ul').prev('button').html(data.newStatus.name+' <span class="caret"></span>');
                            obj.parent('ul').prev('button').attr('data-status', data.newStatus.proco_status_id);
                            $('#'+idproduct).removeClass('status_'+oldStatus);
                            $('#'+idproduct).addClass('status_'+newStatus);
                        }
                        obj.parent('ul').prev('button').removeClass('disabled');
                        $('.hadToRefresh').val(0);
                    },
                    error: function() {
                        $('.flash_errors').html('Impossible de joindre le serveur !!!');
                        obj.parent('ul').prev('button').removeClass('disabled');
                        $('.hadToRefresh').val(0);
                    }
                });
            }
        });
    }
    
    // permet de rendre toute la case qui englobe le lien cliquable
    $(".liste_poste").on('click','.proco_pile_top',function( event ) {
        event.stopPropagation();
        event.preventDefault();
        $('.hadToRefresh').val(1);
        if($(this).hasClass('clicked')){
            return false;
        }
        $(this).addClass('clicked');
        $(this).click(function(event){
            event.preventDefault();
        });
       go_to_cooked($(this));
    // Ancienne methode
    // window.location.replace($(this).children("a").attr("href"));
    });
    
    //Vérifie les commentaires de chaque pile pour savoir si il y en a ou pas et ajout d'un icone dans le code si c'est le cas
    
   $('a.cook').click(function(event) {
        event.stopPropagation();
        event.preventDefault(event);
    });
    refresh_proco();
});