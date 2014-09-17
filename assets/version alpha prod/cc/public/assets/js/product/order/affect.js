$(function() {
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