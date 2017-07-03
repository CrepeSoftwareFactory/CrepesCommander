//Envoi du formulaire de création de commande Ajax
$( document ).ready(function() {
    $('.flash_success').hide();
    $('.flash_errors').hide();
    
    $('.nbPiles').change(function() {
        $('.nbPiles .dropdown-toggle').addClass('hide');
        $('.nbPiles').append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $('.flash_success').hide();
        $('.flash_errors').hide();
        var nbPiles = $(this).val();
        $.ajax({
            url: '/rest/admin/changeNbPiles.json',
            type: 'post',
            dataType: 'json',
            data: { nbPiles: nbPiles},
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
});