$(function() {
    $('a.btn-primary.btn-lg').one('click', function(e){
        
        e.preventDefault();
        
        var parent = $(this).parent();
        
        var prev_proco = $('a.active', parent);
        prev_proco.removeClass('active');
        
        $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
       
        var id_station = $(this).attr('data-station');
        var id_produit = $(this).attr('data-product');
        
        if(id_station==0){
            id_station = null;
        }
        
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
                }
            },
            error: function() {
                $('.flash_errors').html('Impossible de joindre le serveur !!!');
                prev_proco.addClass('active');
            }
        });
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
    
    $('a.product-delete').on('click', function(e) {
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
});