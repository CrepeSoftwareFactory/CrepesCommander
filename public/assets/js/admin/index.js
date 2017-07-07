//Scripts de la page /admin => Admin
//Envoi du formulaire de création de commande Ajax
$( document ).ready(function() {
    $('.flash_success').hide();
    $('.flash_errors').hide();
    
    $('table input').keyup(function(){
       $(this).addClass('redText');
    });
    
    $('#myModal').on('hidden.bs.modal', function () {
        $('.modal-body').empty();
        $('.btn-sauvegarder').remove();
    });
    
    $('#addNote').on('click', function(e){
        $('#myModal').modal('show');
        $('.modal-body').append("<h2>Ajouter une note : </h2>");
        $('.modal-body').append('<textarea rows="4" cols="50" class="textToSend" name="content"></textarea>');
        $('.modal-footer').prepend('<button type="button" class="btn btn-primary btn-sauvegarder" data-dismiss="modal">Sauvegarder</button>');
        $('.btn-sauvegarder').click(function(e){
            e.preventDefault();
            var content = $(".textToSend").val();
            $.ajax({
                url: '/rest/admin/addNote.json',
                type: 'post',
                dataType: 'json',
                data: { content: content},
                success: function(data) {
                    if (data.error) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        $("#tableAffichageNotes").append(data.html);
                        bindSupNotToBtn();
                    }
                }
            });
        });
    });
    
    //Fonction qui va bind fonction de suppression via ajax au boutton de suppression des notes
    function bindSupNotToBtn(){
        $('.supNote').on('click', function(e){
            e.preventDefault();
            $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            var idNote = $(this).attr('data-idNote');
            var obj = $(this);
            $.ajax({
                url: '/rest/admin/delNote.json',
                type: 'post',
                dataType: 'json',
                data: { idNote: idNote},
                success: function(data) {
                    if (data.error) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        obj.parent('.globalNote').remove();

                    }
                }
            });
        });
    }
    
    bindSupNotToBtn();
    


    $('#addTypeProduct').click(function(){
        $('#myModal').modal('show');
        $('.modal-body').append("<h2>Ajouter un Type de produit : </h2>");
        var html = '<table class="table table-bordered table-hover"><tr style="background-color: #FFF;"><th>NOM</th><th>COULEUR</th></tr>';
        html += '<tr><td><input type="text" class="nomToSend" name="type_label" placeholder="Veuillez saisir le nom..."/></td>';
        html += '<td><input type="text" class="couleurToSend" name="type_couleur" placeholder="Veuillez saisir une couleur html..."/></td></tr>';
        $('.modal-body').append(html);
        $('.modal-footer').prepend('<button type="button" class="btn btn-primary btn-sauvegarder" data-dismiss="modal">Sauvegarder</button>');
        $('.btn-sauvegarder').click(function(e){
            e.preventDefault();
            var nom = $(".nomToSend").val();
            var couleur = $(".couleurToSend").val();
            $.ajax({
                url: '/rest/admin/addTypeProduct.json',
                type: 'post',
                dataType: 'json',
                data: { nom: nom, couleur: couleur},
                success: function(data) {
                    if (data.error) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                    location.reload();
                }
            });
        });
    });
    
    $('#addProduct').click(function(){
        $('#myModal').modal('show');
        $('.modal-body').append("<h2>Ajouter un produit : </h2>");
        var html = '<table class="table table-bordered table-hover"><tr style="background-color: #FFF;"><th>CODE</th><th>NOM</th><th>PRIX</th><th>TYPE</th></tr>';
        html += '<tr><td><input type="text" style="width:100px" class="codeToSend" name="code" placeholder="Veuillez saisir le code..."/></td>';
        html += '<td><input type="text" class="nameToSend" name="name" placeholder="Veuillez saisir le nom..."/></td>';
        html += '<td><input type="number" style="width:70px" class="prixToSend" name="price" placeholder="Veuillez saisir un prix..."/></td>';
        html +=  '<td><select id="typeAddProduct" data-fct="changeProduct" class="form-control"></select></td></tr>';
        $.ajax({
            url: '/rest/admin/getTypes.json',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                } else {
                    var types = data.types;
                    for(var i=0; i<types.length; i++){
                        $('#typeAddProduct').append('<option value="'+types[i].type_id+'">'+types[i].type_label+'</option>');
                    }
                }
            }
        });
        $('.modal-body').append(html);
        $('.modal-footer').prepend('<button type="button" class="btn btn-primary btn-sauvegarder" data-dismiss="modal">Sauvegarder</button>');
        $('.btn-sauvegarder').click(function(e){
            e.preventDefault();
            var code = $(".codeToSend").val();
            var name = $(".nameToSend").val();
            var prix = $(".prixToSend").val();
            var type = $("#typeAddProduct").val();
            $.ajax({
                url: '/rest/admin/addProduct.json',
                type: 'post',
                dataType: 'json',
                data: { code: code, name: name, prix: prix, type: type},
                success: function(data) {
                    if (data.error) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                    location.reload();
                }
            });
        });
    });
    
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
    
    $('table button').click(function(e){
        $('.flash_success').hide();
        $('.flash_errors').hide();
        var obj = $(this);
        var html=$(this).html();
        $(this).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        var id = $(this).attr('data-id');
        var fct = $(this).attr('data-fct');
        var champ = $(this).prev('input').attr('name');
        var value = $(this).prev('input').val();
        $.ajax({
            url: '/rest/admin/'+fct+'.json',
            type: 'post',
            dataType: 'json',
            data: { id: id, value: value, champ: champ, fct: fct},
            error: function() {
                obj.html(html);
                obj.prev('input').removeClass('redText');
            },
            success: function(data) {
                if (data.error) {
                    $('.flash_errors').html(data.message).show();
                    obj.html(html);
                } else {
                    $('.flash_success').html(data.message).show();
                    obj.html(html);
                    if(champ == "type_couleur"){
                        console.log('in');
                        obj.closest('tr').css("background-color", value);
                    }else if(fct == "supTypeProduct" || fct == "supProduct"){
                        obj.closest('tr').remove();
                    }
                }
                obj.prev('input').removeClass('redText');
            }
        });
    });
    
    $('.tableProduits select').change(function(){
        var fct = $(this).attr('data-fct');
        var id = $(this).attr('data-id');
        var value = $( "option:selected", this).val();
        var obj = $(this);
        if($(this).hasClass('chgeClose')){
            var champ = 'close';
        }
        else{
            var champ = 'type';
        }
        $.ajax({
            url: '/rest/admin/'+fct+'.json',
            type: 'post',
            dataType: 'json',
            data: { id: id, value: value, champ: champ, fct: fct},
            success: function(data) {
                if (data.error) {
                    $('.flash_errors').html(data.message).show();
                } else {
                    $('.flash_success').html(data.message).show();
                }
            }
        });
    });
});