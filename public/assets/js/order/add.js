// JavaScript Document

// Fonctions de traitement calculator

function cmde_confirm() {
	var cmde = new Object();
	cmde["produits"] = new Object();
	var i = 0;
	$("#liste_achats LI").each(function() {
		cmde["produits"]["produit_"+i] = new Object();
		var produit = cmde["produits"]["produit_"+i];
		
		produit["produit_id"] = $(this).data("id");
		produit["produit_qtt"] = $(this).data("qtt");
		produit["produit_valeur"] = $(this).data("valeur");
		
		i++;
	});	
	cmde["total"] = $("#panier #total").data("valeur");
	
	//console.log(cmde);
}

function calcul_new_qtt(cible,qtt_depart,diff) {
	qtt_depart = parseInt(qtt_depart);
	diff = parseInt(diff);
	
	var qtt_final = qtt_depart + diff;
	return qtt_final;
}

function display_new_qtt(cible,qtt) {
	cible.data("achat-qtt",qtt);
	cible.find(".achat_qtt").html(qtt);
        cible.find('input[type="hidden"].quantity').val(qtt);
}

function update_achat_total(cible) {
	var total = parseFloat(cible.data("achat-qtt")) * parseFloat(cible.data("valeurmarchande"));
	cible.data("achat_total",total);
	//console.log('cible = '+cible+' && cible.data("achat_total") = '+cible.data("achat_total"));
	cible.find(".achat_total").html(total);
        
	//console.log('cible = '+cible+' && cible.data("achat_total") = '+cible.data("achat_total"));
	cible.find(".price").val(total);
	update_total();
}

function update_total() {
	var total = 0;
	$("#liste_achats LI").each(function() {
		//console.log('$(this) = '+$(this)+' && $(this).data("achat_total") = '+$(this).data("achat_total"));
		var current_total = parseFloat($(this).data("achat_total"));
		//console.log('$(this).data("achat_total") = ' + $(this).data("achat_total"));
                if ($(this).find('input[type="checkbox"].free:checked').length === 0) {
                    total = parseFloat(total) + parseFloat(current_total);
                }
//		console.log("total = "+parseFloat(total));
	});
	$("#panier #total").data("valeur",total);
	$("#panier #total").html(total);
        
        $("#panier #monnaie").keyup();
}

function modify_cart(type) { /* type = expanded || summary */
    //$('#myModal').modal('toggle');
    
    if(type == "expanded") {
        $('#myModal').modal('show');
        $("#panier").appendTo( "#myModal .modal-body" );
        $("#panier").removeClass( "summary" );
        $("#panier").addClass( "expanded" );
    } else if(type == "summary") {
        //$('#myModal').modal('toggle'); type = close est lancé quand la methode de fermture du modal est déjà lancée
        $("#panier").appendTo( "#col_panier" );
        $("#panier").removeClass( "expanded" );
        $("#panier").addClass( "summary" );
        
    }
}

/* Function de rendu monnaie ! Param : champ de saisie de la monnaie*/
function calcul_monnaie(champ_monnaie,total) {
    var valeur_monnaie = champ_monnaie.val();
    var total = parseFloat(total);
    
    if(valeur_monnaie > total) {
        var valeur_a_rendre = valeur_monnaie - total;
    } else {
        var valeur_a_rendre = 0;
    }
    
    return valeur_a_rendre;
}
/* Fin Function de rendu monnaie ! */

$( document ).ready(function() {
    
    // Initialisation du LI de base qui sera clonÃ©
    var li_achat_base = $("#liste_achats #default");
    
    // On cache les élémens dynamiques
    //$("#recap_cmdes .recap_cmde.default").hide();

    $("#choix_produit").on('click','a',function( event ) {
            //On rÃ©cupÃ¨re toutes les infos du produit cliquÃ©
            var produit_ref = $(this);
            var produit_id = produit_ref.data("id");
            var produit_valeur = produit_ref.data("valeurmarchande");
            var produit_libelle = produit_ref.data("nomcomplet");

            // Si le produit selectionnÃ© existe dÃ©jÃ  dans le panier
            if($("#liste_achats LI[data-id="+produit_id+"]").length) {
                    var edit_produit_ref = $("#liste_achats LI[data-id="+produit_id+"]");

                    var qtt_produit = parseInt(edit_produit_ref.data("achat-qtt"));

                    qtt_produit = calcul_new_qtt(edit_produit_ref,qtt_produit,1); // Ajoute 1			
                    display_new_qtt(edit_produit_ref,qtt_produit);
                    update_achat_total(edit_produit_ref);
            } else {		
                    // Si le produit n'a jamais Ã©tÃ© rajoutÃ©
                    li_achat_base.appendTo("#liste_achats");
                    li_achat_base.clone().attr( "id", "new" ).appendTo("#liste_achats");
                    var add_produit_ref = $("#new");
                    add_produit_ref.find(".achat_libelle").html(produit_libelle);

                    add_produit_ref.attr("data-id",produit_id);
                    add_produit_ref.attr("data-valeurmarchande",produit_valeur);
                    add_produit_ref.attr("data-achat-qtt",4);
                    add_produit_ref.attr("data-achat-total",produit_valeur);
                    // derniere etape
                    add_produit_ref.attr("id","cart_"+produit_id);
                    add_produit_ref.removeClass("achat_default");
                    add_produit_ref.find('input[type="hidden"].quantity').attr('name', 'products['+produit_id+'][quantity]');
                    add_produit_ref.find('input[type="text"].price').attr('name', 'products['+produit_id+'][price]');
                    add_produit_ref.find('input[type="text"].comment').attr('name', 'products['+produit_id+'][comment]').val(null);
                    add_produit_ref.find('input[type="checkbox"].free').attr('name', 'free['+produit_id+'][free]');

                    li_achat_base.detach();
                    //$("#liste_achats #default").remove();

                    display_new_qtt(add_produit_ref,"1");
                    update_achat_total(add_produit_ref);
            }
    });

    $("#liste_achats").on('click','.achat_btn_qtt',function( event ) {
            var btn_qtt = $(this);
            qtt_produit = calcul_new_qtt(btn_qtt,btn_qtt.parent().data("achat-qtt"),btn_qtt.data("diff"));
//            console.log("qtt_produit = "+qtt_produit);
            display_new_qtt(btn_qtt.parent(),qtt_produit);
            update_achat_total(btn_qtt.parent());
    });

    $("#liste_achats").on('change','.price',function( event ) {
            update_achat_total(btn_qtt.parent());
    });
    
     $("#liste_achats").on('click','.free', function( event ) {
            var btn = $(this);
            update_achat_total(btn.parent());
    });

    $("#panier").on('click','#cmde_confirm',function( event ) {
            cmde_confirm();
    });
    
    $("#panier").on('click','#liste_achats',function( event ) {
        if ($('#panier').hasClass('summary')) {
            modify_cart("expanded");
        }
    });
    
    $('#myModal').on('hidden.bs.modal', function (e) {
        modify_cart("summary");
    });
    
    //Envoi du formulaire de création de commande Ajax
    $('form#formulaire_panier').submit(function() {
        if ( $.trim($( "#lastname" ).val()) == '') {
            alert('Il faut saisir un nom !')
            return false;
        }
        
        $('input#submit').val('En cours de création...');
        $('.flash_success').hide();
        $('.flash_errors').hide();
        
        $.ajax({
            url: '/rest/order/add.json',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if (data.error) {
                    $('.flash_errors').html(data.message).show();
                } else {
                    $('ul#liste_achats').empty();
                    $('input#lastname').val(null);
                    $('span#total').html(0);
                    $('span#monnaie_rendu').html(0);
                    $('input#monnaie').val(null);
                    
                    $('.flash_success').html(data.message).show();
                }
                
                $('input#submit').val('Valider et payer');
            }
        });
        return false;
    });

    /*$("#panier").on('click','#submit',function( event ) {
        event.preventDefault();
        //alert("test");
    });*/
    
    /* gestion de la monnaie */
    $("#panier #monnaie").keyup(function( event ) {
        var cible = $(this);
        var total = $("#panier #total").data("valeur"); 
        //var total = $("#panier #total").html(); 
        $("#panier SPAN#monnaie_rendu").html(calcul_monnaie(cible,total));
    });

});

