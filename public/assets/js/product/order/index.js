//Scripts de la page /product/order => Au Boulot !
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
    
    //Fonction qui va recharger la page si elle n'est pas déjà entrain de le faire
    function reloadPage(){
        //On récupère la variable de rafraichissement
        var hadToRefresh = $('.hadToRefresh').val();
        //Si elle est à 0
        if(hadToRefresh == 0){
            //On lance le rafraichissement des piles et des unaffected
            go_to_refresh();
            go_to_refresh_unaffected();
        }
    }
    
    //Fonction pour vérifier si pile est vide on affiche qu'il n'y a pas de commande dans la pile
    function verif_no_proco_pile(){
        $('.proco_pile_top').each(function(){
            var nb_proco = 0;
            var parent = $(this).parent('ul');
            //On récupère le nombre de proco dans la file d'attente
            nb_proco = $('.proco_pile_waiting', parent).length;
            //Si ce nombre = 0 => On affiche qu'il n'y a pas de commande en attente
            if(nb_proco == 0){
                parent.append('<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>');
            }
        });
    }
    
    //Fonction avec ajax pour rempiler une crêpes qui aurait été terminé par erreur
    function rempil_proco(){ 
        $(".rempiler").on('click', function(e){
            e.preventDefault();
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            var idPile = $(this).attr('')
            var productId = $(this).attr('data-idProCo');
            $.ajax({
                url: '/rest/product/order/rempiler.json',
                type: 'post',
                dataType: 'json',
                data: {productId: productId},
                success: function(data){
                    if (data.error==true) {
                    } else {
                        $('.flash_success').html(data.message);
                    }
                }
            });
        });
    }
    
    rempil_proco();
    
    //Fonction ajax qui va mettre à jout les derniers produits passés 
    //id : id de la pile
    function last_product(id){
        $.ajax({
            url: '/rest/product/order/refresh_lastCooked.json',
            type: 'post',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                if (data.error==true) {
                    $('.flash_errors').html(data.message);
                } else {
                    $("#lastCooked"+id).html(data.message);
                    rempil_proco();
                }
            }
        });
    }
    
    //Fonction Quand clic du cuistot sur une proco d'une pile en cours de fabrication => La valide / Cherche une nouvelle proco à mettre / Rafraichit les éléments
    //In : Html => .Proco_Pile_Top
    function go_to_cooked(obj){
        $('.hadToRefresh').val(1);
        var proco_pile = obj;
        var href = $('.cook', obj).attr('href');
        var product_before = proco_pile.attr('id'); // stock l'id de la proco qui viens d'être terminée et qui est encore affichée
        if(href){
            var contenuObj = obj.html();
            obj.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            var id_commande = href.substr(href.lastIndexOf('/')+1); // numéro de la pile
            $("#lastCooked"+id_commande).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
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
                        // data.idProduct = id du prochain produit à afficher
                        proco_pile.empty();
                        proco_pile.html(data.message);
                        var parent = proco_pile.parent();
                        proco_pile.attr('id', data.idProduct);
                        $('.flash_success').html('Pile mise à jour');
                    }
                    proco_pile.removeClass('clicked');
                    $('.hadToRefresh').val(0);
                    verif_no_proco_pile();
                    start_chrono(id_commande);
                    last_product(id_commande);
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
    
    //Fonction qu'on appel quand on veut rafraichir les piles
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
                last_product(id_commande);
            }
        });
    }
    
    //Fonction qu'on appel quand on veut rafraichir les proco non affecté à une pile
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
    //On rafraichit les proco
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
//            $('.modif_status .dropdown-toggle').addClass('disabled', true);
//            $('.modif_pile .dropdown-toggle').addClass('disabled', true);
            $.ajax({
                url: '/rest/product/order/status.json',
                type: 'post',
                dataType: 'json',
                data: {id: id},
                success: function(data) {
                    var messageComment = "Pas de commentaires !";
                    if (data.error) {
                        $('.flash_errors').html(data.message).show();
//                        $('.modif_pile .dropdown-toggle').removeClass('disabled');
//                        $('.modif_status .dropdown-toggle').removeClass('disabled');
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
                        
//                        $('.modif_pile .dropdown-toggle').removeClass('disabled');
//                        $('.modif_status .dropdown-toggle').removeClass('disabled');
                        
                        $('.modal-header').empty();
                        $('.modal-header').append(data.title);
                    }
//                    $('.modif_pile .dropdown-toggle').removeClass('disabled');
//                    $('.modif_status .dropdown-toggle').removeClass('disabled');
                    
                    obj.css('background-color', '');
                    $('.hadToRefresh').val(0);
                    
                }
            });
            $('#myModal').modal('show');
//            $('.modif_pile .dropdown-toggle').removeClass('disabled');
//            $('.modif_status .dropdown-toggle').removeClass('disabled');
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
//            $('.modif_status .dropdown-toggle').addClass('disabled', true);
//            $('.modif_pile .dropdown-toggle').addClass('disabled', true);
            $.ajax({
                url: '/rest/product/order/status.json',
                type: 'post',
                dataType: 'json',
                data: {id: id},
                success: function(data) {
                    var messageComment = "Pas de commentaires !";
                    if (data.error) {
                        $('.flash_errors').html(data.message).show();
//                        $('.modif_pile .dropdown-toggle').removeClass('disabled');
//                        $('.modif_status .dropdown-toggle').removeClass('disabled');
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
//                    $('.modif_pile .dropdown-toggle').removeClass('disabled');
//                    $('.modif_status .dropdown-toggle').removeClass('disabled');
                    
                    obj.css('background-color', '');
                    $('.hadToRefresh').val(0);
                    
                }
            });
            $('#myModal').modal('show');
            
//            $('.modif_pile .dropdown-toggle').removeClass('disabled');
//            $('.modif_status .dropdown-toggle').removeClass('disabled');
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
        $('.modif_status li').on('click', function(){
            $('.hadToRefresh').val(1);
            var newStatus = $('button', this).attr('data-status');
            var idproduct = $('button', this).attr('data-idproduct');
            var isTop = $('#'+idproduct).hasClass('proco_pile_top');
            var oldStatus = $(this).parent('ul').prev('button').attr('data-status');
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
    
    // Fonction pour initialiser les chronos JS qui s'affichent sur les différentes proco en cours de prepa
    function init_chronos() {
        var array_chronos = new Array();
        $('body').data('array_chronos',array_chronos);
        
        for (var iter = 1; iter <= 10; iter++) {
            array_chronos[iter] = new Array();
            array_chronos[iter]['minutes'] = 0;
            array_chronos[iter]['secondes'] = 0;
            array_chronos[iter]['timer'] = false;
        }
        
        //var chrono_1 = array_chronos[0] ({"minutes":0, "sec":0});
    }
    
    function reset_chronos(id_chrono) {
        clearTimeout(array_chronos[id_chrono]['timer']);
        // TODO : reset dynamique des chronos et passage en parametre d'un id chrono ou de "all"
        console.log("reset du crono "+  id_chrono);
        if(id_chrono == "all") {
            for (var iter = 1; iter <= 10; iter++) {
                array_chronos[iter] = new Array();
                array_chronos[iter]['minutes'] = 0;
                array_chronos[iter]['secondes'] = 0;
                array_chronos[iter]['timer'] = false;
            }
        } else {
            array_chronos[id_chrono] = new Array();
            array_chronos[id_chrono]['minutes'] = 0;
            array_chronos[id_chrono]['secondes'] = 0;
            array_chronos[id_chrono]['timer'] = false;
        }
    }
    
    function stop_chronos() {
        
    }
    
    function start_chrono(id_chrono) {
        
        array_chronos = $('body').data('array_chronos');
        chrono_params = array_chronos[id_chrono];
        
        console.log("chrono_params['timer'] = "+chrono_params["timer"]);
        if(chrono_params["timer"] === false) {
            //timer false donc on lance
            chrono_params["timer"] == 0;
            boucle_chrono(id_chrono);
        } else {
            restart_chrono(id_chrono);
        } 
    }

    function restart_chrono(id_chrono) {
        reset_chronos(id_chrono);
        boucle_chrono(id_chrono);
    }
    
    function boucle_chrono(id_chrono){
        // la fonction se lance toutes les secondes 
        var frequence_chrono = 1000;
        array_chronos = $('body').data('array_chronos');
        minu = array_chronos[id_chrono]["minutes"];
        secon = array_chronos[id_chrono]["secondes"];
        secon++;
        if (secon > 59){ 
            secon = 0; 
            minu ++ ;
        } //si les secondes > 59, on les réinitialise à 0 et on incrémente les minutes de 1
        
        var display_time = minu +":"+secon;
        $("#liste_poste_"+id_chrono+" .label-poste #chrono-proco").html(display_time);
        //console.log("chrono "+id_chrono+" : " + minu +":"+secon );
        array_chronos[id_chrono]["minutes"] = minu;
        array_chronos[id_chrono]["secondes"] = secon;
        compte = array_chronos[id_chrono]["timer"];
        compte = setTimeout(function(){ boucle_chrono(id_chrono); },frequence_chrono) ; //la fonction est relancée 
        array_chronos[id_chrono]["timer"] = compte ;
    }
    
    init_chronos();
    
    // permet de rendre toute la case qui englobe le lien cliquable
    $(".liste_poste").on('click','.proco_pile_top',function( event ) {
        //array_chronos = $('body').data('array_chronos');
        //start_chrono(0);
        //if(array_chronos[0]["timer"] == false) {
            
        /*} else {
            console.log("stop du chrono");
            reset_chronos();
       }*/
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