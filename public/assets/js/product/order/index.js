$(function() {
    var gotoRefresh = '';
    $("#icon_refresh").fadeTo(1, 0); // cache l'icone qui permet de savoir quand les refresh sont lancés
    $('.notSelectable').disableSelection(); // active un script qui rend certains textes non selectionnable avec un taphold
    function refreshPage(){
        // Fonction pour rafraichir la page en Ajax toutes les 5 secondes
        gotoRefresh = setInterval(function(){
            reloadPage();
        }, 5000);
    }
    refreshPage();
    
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
            if(nb_proco == 0){
                parent.append('<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>');
            }
        });
    }
    
    function go_to_cooked(obj){
        $('.hadToRefresh').val(1);
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
                    } else {
                        $('#'+data.idProduct).remove();
                        proco_pile.empty();
                        proco_pile.html(data.message);
                        var parent = proco_pile.parent();
                        proco_pile.attr('id', data.idProduct);
                        $('.flash_success').html('Pile mise à jour');
                    }
                    proco_pile.removeClass('clicked');
                    $('.hadToRefresh').val(0);
                    verif_no_proco_pile();
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
            if(href){
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
                            if($('.hadToRefresh').val()==0){
                                parent.empty();
                                parent.html('<li class="btn btn-default btn-lg btn-block proco_pile_top" id="'+data.idProduct+'"></li>');
                                $('li', parent).html(data.message);
                            }
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
                    //$('.flash_success').html('Refresh !');
                    //$('#icon_refresh').css('opacity');
                    $("#icon_refresh").fadeTo(1, 100);
                    window.setTimeout(function() {
                        $("#icon_refresh").fadeTo(500, 0, function(){
                            //$(this).remove(); 
                            //$('#icon_refresh').addClass('fade');
                        });
                    }, 2000);
                    //$('#icon_refresh').addClass('fade');
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
                        $('.modif_pile .dropdown-toggle').removeClass('disabled');
                        $('.modif_status .dropdown-toggle').removeClass('disabled');
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
            $('.modif_pile .dropdown-toggle').removeClass('disabled');
            $('.modif_status .dropdown-toggle').removeClass('disabled');
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
                        $('.modif_pile .dropdown-toggle').removeClass('disabled');
                        $('.modif_status .dropdown-toggle').removeClass('disabled');
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
            
            $('.modif_pile .dropdown-toggle').removeClass('disabled');
            $('.modif_status .dropdown-toggle').removeClass('disabled');
        });
    }
    
    maj_status();
    //Fonction de maj de status d'un proco
    function maj_status(){
        //Fonction avec Ajax pour modifier et changer la pile d'une proco depuis la modale
        $('.modif_pile li').on('click', function(){
            $('.hadToRefresh').val(1);
            var newPile = $('button', this).attr('data-pile');
            var idproduct = $('button', this).attr('data-idproduct');
            var hasCooked = $('#'+idproduct).hasClass('proco_pile_top');
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
                                if(!hasCooked){
                                    $('#'+idproduct).remove();
                                }else{
                                    var pile = $('#'+idproduct);
                                    pile.attr('id', '');
                                    var href = $('.cook', pile).attr('href');
                                    var id_commande = href.substr(href.lastIndexOf('/')+1);
                                    pile.html('<a class="cook" href="http://localhost/product/order/cook/'+id_commande+'">Vide !</a></li>');
                                    
                                }
                                verif_no_proco_pile();
                                go_to_refresh_unaffected();
                            }
                            else{
                                obj.parent('ul').prev('button').html(data.newPile.name+' <span class="caret"></span>');
                                obj.parent('ul').prev('button').attr('data-pile', data.newPile.station_id);
                                var pile = $('#'+idproduct);
                                if(pile.hasClass('proco_pile_top')){
                                    var href = $('.cook', pile).attr('href');
                                    var id_commande = href.substr(href.lastIndexOf('/')+1);
                                    pile.attr('id', '');
                                    pile.html('<a class="cook" href="http://localhost/product/order/cook/'+id_commande+'">Vide !</a></li>');
                                }else{
                                    $('#'+idproduct).remove();
                                    if($('#liste_poste_'+data.newPile.station_id+' .panel-default .proco_pile_waiting').html()=='Aucune commande en attente.'){
                                        $('#liste_poste_'+data.newPile.station_id+' .panel-default .proco_pile_waiting').empty();
                                    }
                                    $('#liste_poste_'+data.newPile.station_id+' .panel-default').append(pile);
                                }
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
        $('.modif_status li').on('click', function(e){
            e.preventDefault();
            $('.hadToRefresh').val(1);
            var newStatus = $('button', this).attr('data-status').toString();
            var idproduct = $('button', this).attr('data-idproduct');
            var isTop = $('#'+idproduct).hasClass('proco_pile_top');
            var oldStatus = $(this).parent('ul').prev('button').attr('data-status').toString();
            if( newStatus !== oldStatus ){
                var obj = $(this);
                obj.parent('ul').prev('button').addClass('disabled');
                obj.parent('ul').prev('button').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                $.ajax({
                    url: '/rest/product/order/change_status.json',
                    type: 'post',
                    dataType: 'json',
                    data: {idProduct: idproduct, newStatus: newStatus, isTop: isTop},
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
                        if(isTop && newStatus==3){
                            var top = $('#'+idproduct);
                            top.attr('id', '');
                            var htmlBefore = $('a.cook', top).html();
                            $('a.cook', top).html('Vide !');
                            top.parent('ul').append('<li class="panel-body proco_pile_waiting status_3" id="'+idproduct+'">'+htmlBefore+'</li>');
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
            $('.hadToRefresh').val(0);
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

// This jQuery Plugin will disable text selection for Android and iOS devices.
// Stackoverflow Answer: http://stackoverflow.com/a/2723677/1195891
$.fn.extend({
    disableSelection: function() {
        this.each(function() {
            this.onselectstart = function() {
                return false;
            };
            this.unselectable = "on";
            $(this).css('-moz-user-select', 'none');
            $(this).css('-webkit-user-select', 'none');
        });
    }
});