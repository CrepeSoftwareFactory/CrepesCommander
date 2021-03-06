// Onrécupère la pile actuelle 
var maPile =  "";

function changeTitlePile(pile){
    $('#currentPileTitle').empty();
    if(pile != null && pile != ""){
        $('#currentPileTitle').append('Pile actuelle : '+pile);
    }
}

function getPile(){
    $.ajax({
        url: '/rest/product/order/getPile.json',
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
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
                maPile = data.value;
                if(maPile != undefined)
                {
                    changeTitlePile(maPile);
                }
                
            }
            displayEmptyPile();
        }
    });
}
getPile();

function displayEmptyPile() {
    if(maPile != "" && maPile != null){
        $('#emptyPile').show();
    }
    else{
        $('#emptyPile').hide();
    }
}

displayEmptyPile();

$('#maPile').change(function() {
    // $('#maPile .dropdown-toggle').addClass('hide');
    $('#maPile').append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $('.flash_success').hide();
    $('.flash_errors').hide();
    maPile = $(this).val();
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
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            }
            getPile();
        }
    });
});

$('#emptyPile').click(function(){
    $.ajax({
        url: '/rest/product/order/emptyPile.json',
        type: 'post',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                $('.flash_errors').html(data.message).show();
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            } else {
                $('.flash_success').html(data.message).show();
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
                getPile();
                $('#maPile').val('').change();
            }
        }
    });
})