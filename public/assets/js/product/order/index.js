$(function() {
    
    // permet de rendre toute la case qui englobe le lien cliquable
    $(".liste_poste").on('click','.proco_pile_top',function( event ) {
        window.location.replace($(this).children("a").attr("href"));
    });
    
    // permet de dÃ©clencher une popup si on reste le doigts sur le bouton
    $( ".proco_pile_top" ).bind( "taphold",function( event ) {
        $('#myModal').modal('show');
        //alert("dfs");
        //event.preventDefault(event);
    });
    
    $('a.cook').click(function() {
        /* a rebrancher quand l'ajax sera pret
        $.ajax({
            url: '/rest/product/order/cook.json',
            type: 'post',
            dataType: 'json',
            data: '',
            success: function(data) {
                if (data.error) {
                } else {
                }
                
                $('input#submit').val('Valider et payer');
            }
        });
        return false;
        */
    });
});