$("#icon_refresh").hide();

$('#maPile').change(function() {
    $('.hadToRefresh').val(1);
    // $('#maPile .dropdown-toggle').addClass('hide');
    $('#maPile').append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $('.flash_success').hide();
    $('.flash_errors').hide();
    var maPile = $(this).val();
    $.ajax({
        url: '/rest/product/order/setPile.json',
        type: 'post',
        dataType: 'json',
        data: { maPile: maPile},
        success: function(data) {
            if (data.error) {
                $('.flash_errors').html(data.message).show();
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            } else {
                $('.flash_success').html(data.message).show();
                window.location.reload();
            }
            $('.hadToRefresh').val(0);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('.flash_errors').html(xhr.status+ ' - Erreur : ' + thrownError).show();
            $('.hadToRefresh').val(0);
        }
    });
});

// Fonction pour attribuer toutes les piles d'une commande à une pile sélectionné au préablable
function setPileToOrder(idPile, idOrder)
{
    $('.hadToRefresh').val(1);
    $('#affect'+idOrder).append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $('.flash_success').hide();
    $('.flash_errors').hide();
    $.ajax({
        url: '/rest/product/order/changePileOrder.json',
        type: 'post',
        dataType: 'json',
        data: { idOrder: idOrder, idStation: idPile},
        success: function(data) {
            if (data.error) {
                $('.flash_errors').html(data.message).show();
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            } else {
                $('.glyphicon-refresh-animate').remove();
                $('.flash_success').html(data.message).show();
                window.location.reload();
            }
            $('.hadToRefresh').val(0);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('.flash_errors').html(xhr.status+ ' - Erreur : ' + thrownError).show();
            $('.hadToRefresh').val(0);
        }
    });
}

// Fonction pour mettre toutes les proco d'une commande avec une date de fin et pour mettre la commande en statut délivrée
function setFinishOrder(idOrder)
{
    $('.hadToRefresh').val(1);
    $('#finish'+idOrder).append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $('.flash_success').hide();
    $('.flash_errors').hide();
    $.ajax({
        url: '/rest/product/order/finishOrder.json',
        type: 'post',
        dataType: 'json',
        data: { idOrder: idOrder},
        success: function(data) {
            if (data.error) {
                $('.flash_errors').html(data.message).show();
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            } else {
                $('.'+idOrder).remove();
                $('.flash_success').html(data.message).show();
            }
            $('.hadToRefresh').val(0);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('.flash_errors').html(xhr.status+ ' - Erreur : ' + thrownError).show();
            $('.hadToRefresh').val(0);
        }
    });
}

// Fonction pour rafraichir la page
function refreshPage(){
    // Fonction pour rafraichir la page en Ajax toutes les 5 secondes
    gotoRefresh = setInterval(function(){
        reloadPage();
    }, 5000);
}
refreshPage();

//Fonction qui va recharger la page si elle n'est pas déjà entrain de le faire
function reloadPage(){
    $("#icon_refresh").show();
    //On récupère la variable de rafraichissement
    var hadToRefresh = $('.hadToRefresh').val();
    //Si elle est à 0
    if(hadToRefresh == 0){
        //On lance le rafraichissement des piles et des unaffected
        go_to_refresh();
    }
}

//Fonction qu'on appel quand on veut rafraichir les piles
function go_to_refresh(){
    $.ajax({
        url: '/rest/product/order/get_view_list.json',
        type: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.error==true) {
                $('.flash_errors').html(data.message);
            } else {
                $("#bodyTable").empty();
                $("#bodyTable").html(data.message);
            }
            $("#icon_refresh").hide();
        }
    });
    // $('.order-line').each(function(){
    //     var proco_pile = $(this);
    //     var href = $('.cook', this).attr('href');
    //     if(href){
    //         var id_commande = href.substr(href.lastIndexOf('/')+1);
    //         $('.flash_errors').empty();
    //         $('.flash_success').empty();
    //         $.ajax({
    //             url: '/rest/product/order/refresh.json',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {id: id_commande},
    //             success: function(data) {
    //                 if (data.error==true) {
    //                     $('.flash_errors').html(data.message);
    //                 } else {
    //                     var parent = proco_pile.parent();
    //                     if($('.hadToRefresh').val()==0){
    //                         parent.empty();
    //                         parent.html('<li class="btn btn-default btn-lg btn-block proco_pile_top" id="'+data.idProduct+'"></li>');
    //                         $('li', parent).html(data.message);
    //                     }
    //                     $('.proco_pile_waiting', parent).remove();
    //                     parent.append(data.attente);
    //                     $('a', parent).click(function(e){
    //                         e.preventDefault();
    //                     });
    //                     refresh_procopile_top($('li', parent));
    //                 }
    //             },
    //             timeout: function() {
    //                 $('.flash_errors').html('Impossible de joindre le serveur !!!');
    //             },
    //             error: function() {
    //                 $('.flash_errors').html('Impossible de joindre le serveur !!!');
    //             }
    //         });
    //         last_product(id_commande);
    //     }
    // });
}