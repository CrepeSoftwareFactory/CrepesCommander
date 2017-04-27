$(function() {
    function refreshPage(){
        // Fonction pour rafraichir la page en Ajax toutes les 5 secondes
        gotoRefresh = setInterval(function(){
            refresh_pending_orders();
        }, 10000);
    }
    refreshPage();
    
    //Fonction ave requête ajax pour rafraichir la page des commandes en cours
    function refresh_pending_orders(){
        $('.flash_success').empty();
        $('.flash_errors').empty();
        var html ="";
        html = $('.liste_proco_client').html();
        $.ajax({
                url: '/rest/product/order/refresh_pending_orders.json',
                type: 'post',
                dataType: 'json',
                success: function(data){
                    if (data.error==true) {
                        $('.flash_errors').html(data.message);
                    } else {
                        if($('.liste_proco_client').html() != data.response){ 
                            $('.liste_proco_client').empty();
                            $('.liste_proco_client').html(data.response);
                            $('.flash_success').html(data.message);
                            generate_all_script();
                        }
                        else{
                             $('.flash_success').html('rien à mettre à jour !');
                        }
                    }
            
                },
                error: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                }
            });
    }
    function generate_all_script(){
        //Fonction avec requête ajax pour modifier le status de priorité d'une proco
        $('.modif_status li').on('click', function(e){
            e.preventDefault();
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
                            console.log(data);
                            obj.parent('ul').prev('button').html(data.newStatus.name+' <span class="caret"></span>');
                            obj.parent('ul').prev('button').attr('data-status', data.newStatus.proco_status_id);
                        }
                        obj.parent('ul').prev('button').removeClass('disabled');
                    },
                    error: function() {
                        $('.flash_errors').html('Impossible de joindre le serveur !!!');
                        obj.parent('ul').prev('button').removeClass('disabled');
                    }
                });
            }

        });

        //Fonction avec requête ajax pour livrer ou annuler une commande
        function action_commande(type_action, obj, texte_boutton){
            var href = obj.attr('href');
            var order_id = href.substr(href.lastIndexOf('/')+1);
            $.ajax({
                url: '/rest/product/order/'+type_action+'.json',
                type: 'post',
                dataType: 'json',
                data: {order_id: order_id},
                success: function(data){
                    if (data.error==true) {
                        $('.flash_errors').html(data.message);
                    } else {
                        $('.flash_success').html(data.message);
                        obj.closest('tr').remove();
                        $('a.color-pile[data-order="'+order_id+'"]').closest('tr').remove();
                    }
                },
                error: function() {
                    $('.flash_errors').html('Impossible de joindre le serveur !!!');
                    obj.html(texte_boutton);
                }
            });
        };

        //Action du click sur le boutton cancel
        $('a.order-cancel').on('click', function(e) {
            e.preventDefault(); 
            $('.flash_errors').html();
            $('.flash_success').html();
            var obj = $(this);
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('<div>'+$(this).attr('title')+' ?</div>').dialog({
                buttons: {
                    'Oui': function() {
                        action_commande('cancel', obj, 'Annuler cmde');
                        $(this).dialog('close');
                    },
                    'Non': function() {
                        obj.html('Annuler cmde');
                        $(this).dialog('close');
                    }
                },
                close: function( event, ui ) {
                    obj.html('Annuler cmde');
                }
            });
        }); 

        //Action du click sur le boutton c'est livré
        $('a.order-finished').on('click', function(e) {
            e.preventDefault();
            $('.flash_errors').html();
            $('.flash_success').html();
            var obj = $(this);
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('<div>'+$(this).attr('title')+' ?</div>').dialog({
                buttons: {
                    'Oui': function() {
                        action_commande('finish', obj, "C'est livré !");
                        $(this).dialog('close');
                    },
                    'Non': function() {
                        obj.html("C'est livré !");
                        $(this).dialog('close');
                    }
                },
                close: function( event, ui ) {
                    obj.html("C'est livré !");
                }
            });
        }); 

        //Fonction avec requête ajax pour supprimer une ligne de proco
        $('.product-delete').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var id_proco = href.substr(href.lastIndexOf('/')+1);
            var btn_delete = $(this);
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            $('<div>'+$(this).attr('title')+' ?</div>').dialog({
                buttons: {
                    'Oui': function() {
                       $.ajax({
                           url: '/rest/product/order/delete.json',
                            type: 'post',
                            dataType: 'json',
                            data: {product_id: id_proco},
                            success: function(data) {
                                if (data.error==true) {
                                    $('.flash_errors').html(data.message);
                                } else {
                                    $('.flash_success').html(data.message); 
                                    if(data.delete_commande==true){
                                        btn_delete.closest('tr').prev('tr').remove();
                                    }
                                    btn_delete.closest('tr').remove();
                                }
                            },
                            error: function() {
                                $('.flash_errors').html('Impossible de joindre le serveur !!!');
                                btn_delete.html('Supprimer');
                            }
                       });
                       $(this).dialog('close');
                    },
                    'Non': function() {
                        $(this).dialog('close');
                        btn_delete.html('Supprimer');
                    }
                }
            });

        });

        //Fonction avec requete ajax pour modifier une pile ne cliquant sur Pile,P1,P2...Pn
        $('a.btn-primary.btn-lg').on('click', function(e){
            //On empeche toute action sur le click en question une fois un premier click effectué
            e.preventDefault();

            var parent = $(this).parent();

            //On sort automatiquement de la fonction si le click est sur une proco où il y a déjà eu un click effectué ou si celui-ci est déjà activé
            if($(this).hasClass('active') || parent.hasClass('on-load')){
                return false;
            }
            else{ 
                parent.addClass('on-load');
                var prev_proco = $('a.active', parent);
                var new_proco = $(this);
                var proco_html = new_proco.html();
                prev_proco.removeClass('active');
                //Affichage de l'icone animé d'attente
                $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

                var id_station = $(this).attr('data-station');
                var id_produit = $(this).attr('data-product');

                if(id_station==0){
                    id_station = null;
                }
                //On envoie la requête et on affiche le nouveau btn activé avec les bonnes classes et le bon visuel
                $.ajax({
                    url: '/rest/product/order/affect.json',
                    type: 'post',
                    dataType: 'json',
                    data: {station_id: id_station, product_id: id_produit},
                    success: function(data) {
                        if (data.error==true) {
                            $('.flash_errors').html(data.message);
                            prev_proco.addClass('active');
                            new_proco.html(proco_html);
                            parent.removeClass('on-load');
                        } else {
                            if(data.message == ""){
                                console.log('in');
                                $('.color-pile[data-product="'+id_produit+'"]').addClass('active');
                                $('.color-pile[data-product="'+id_produit+'"]').html('Pile');
                            }else{
                                $('.color-poste-'+data.message+'[data-product="'+id_produit+'"]').addClass('active');
                                $('.color-poste-'+data.message+'[data-product="'+id_produit+'"]').html('P'+id_station);
                            }
                            parent.removeClass('on-load');
                        }
                    },
                    error: function() {
                        $('.flash_errors').html('Impossible de joindre le serveur !!!');
                        prev_proco.addClass('active');
                        new_proco.html(proco_html);
                        parent.removeClass('on-load');
                    }
                });
            }
        });
    }
    generate_all_script();
});