<?php
    include("header.php");
?>
    <div>
        <div class="row">
            <div class="col-xs-9 ">
                <div id="choix_produit" >
                    <ul data-role="listview" class="list-unstyled btn-group btn-group-lg type_produit_galettes">
                        <li><a href="#" id="choix_ALL" data-id="G-CPL" data-valeurmarchande="4.5" class="btn btn-default btn-lg">G. Complète</a></li>
                        <li><a href="#" id="choix_SUE" data-id="G-VG" data-valeurmarchande="4.5" class="btn btn-default btn-lg">G. Végé</a></li>
                        <li><a href="#" id="choix_NOR" data-id="G-3FR" data-valeurmarchande="4.5" class="btn btn-default btn-lg">G. 3 Fromages</a></li>
                        <li><a href="#" id="choix_SAU" data-id="G-Sauc" data-valeurmarchande="4.5" class="btn btn-default btn-lg">G. Saucisse</a></li>
                    </ul>
                    <ul data-role="listview" class="list-unstyled btn-group btn-group-lg type_produit_crepes" >
                        <li><a href="#" id="choix_ITA" data-id="C-BS" data-valeurmarchande="2.5" class="btn btn-default btn-lg">C. Beurre Sucre</a></li>
                        <li><a href="#" id="choix_ESP" data-id="C-CHOC" data-valeurmarchande="2.5" class="btn btn-default btn-lg">C. Chocolat</a></li>
                        <li><a href="#" id="choix_GRE" data-id="C-CONF" data-valeurmarchande="2.5" class="btn btn-default btn-lg">C. Confiture</a></li>
                        <li><a href="#" id="choix_ITA" data-id="C-BS" data-valeurmarchande="2.5" class="btn btn-default btn-lg">C. Beurre Sucre</a></li>
                        <li><a href="#" id="choix_ESP" data-id="C-CHOC" data-valeurmarchande="2.5" class="btn btn-default btn-lg">C. Chocolat</a></li>
                       
                    </ul>
                </div>
            </div>

            <div class="col-xs-3 bg-info">
                <div id="panier">
                    <form class="form-horizontal" role="form">
                        
                        <div>    
                            <input type="text" id="nom" class="form-control" placeholder="NOM" />
                        </div>
<!--                        <h4>Panier</h4>-->
                        <ul id="liste_achats" class="list-unstyled">  
                            <li class="achat_default" id="default">
                                <span class="achat_libelle">Aucun produit n'est renseigné</span>
                                <input type="button" value="-" data-diff="-1" class="achat_btn_qtt achat_btn_plus btn btn-primary">
                                <span class="achat_qtt" data-achat-qtt="0">0</span> 
                                <input type="button" value="+" data-diff="1" class="achat_btn_qtt achat_btn_moins btn btn-primary" >
                                = 
                                <span class="achat_total" data-valeur="0">0</span>
                            </li>  	
                        </ul>
                        <div>
                            <h3>Total</h3>
                            <span id="total" data-valeur="0">0</span>€
                        </div>
                        <input id="cmde_confirm" type="button" value="Valider la commande (payé)">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    include("footer.php");
?>