$(function() {
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
                }
            });
        }
    });
    
    $('a.order-cancel').on('click', function(e) {
       var link = this;
       e.preventDefault();

        $('<div>'+$(this).attr('title')+' ?</div>').dialog({
            buttons: {
                'Oui': function() {
                    window.location = link.href;
                },
                'Non': function() {
                    $(this).dialog('close');
                }
            }
        });
    }); 
    
//    $('a.product-delete').on('click', function(e) {
//       var link = this;
//       e.preventDefault();
//
//        $('<div>'+$(this).attr('title')+' ?</div>').dialog({
//            buttons: {
//                'Oui': function() {
//                    window.location = link.href;
//                },
//                'Non': function() {
//                    $(this).dialog('close');
//                }
//            }
//        });
//    }); 
});