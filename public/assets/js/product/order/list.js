$('#maPile').change(function() {
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
                $('.glyphicon-refresh-animate').remove();
                $('.nbPiles .dropdown-toggle').removeClass('hide');
            }
        }
    });
});