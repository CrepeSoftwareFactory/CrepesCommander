<?php echo Form::open(array( // Commenté par Simon le 23/10 : pourquoi ? et de plus attention, ça genere une balise form qui ne se ferme pas
    'action'        => '',
    'name'          => 'order',
    'id'            => 'formulaire_panier'
)); ?> 
<div>
    <div class="row">
        <div class="col-xs-9">
            <div id="choix_produit" >
                <?php foreach ($types as $key => $type) { ?>
                    
                    <ul data-role="listview" class="list-unstyled btn-group btn-group-lg" id="<?php echo strtolower($type); ?>">
                    <?php foreach ($products[$key] as $product) {  ?>
                        <li>                            
                            <?php 
                            $content_a = $product->code." <span>".$product->name."</span>";
                            echo Html::anchor('#', $content_a, array(
                                'id'                    => 'product_'.$product->get_id(),
                                'class'                 => 'btn btn-lg btn-default',
                                'data-id'               => $product->get_id(),
                                'data-valeurmarchande'  => $product->price,
                                'data-nomcomplet'       => $product->name,
                            )); ?>
                        </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>

        <div id="col_panier" class="col-xs-3 bg-info panel panel-default">
            <div id="panier" class="panel-body summary">
<!--                <form class="form-horizontal" role="form" id="formulaire_panier">-->
                    <div id="nom_client">    
                        <?php echo Form::input(array(
                            'id'         => 'lastname',
                            'name'       => 'lastname', 
                            'value'      => $customer->lastname,
                            'class'      => 'form-control',
                            'placeholder' => 'Nom'
                        )); ?>
                    </div>
                    <h3>Panier</h3>
                    <ul id="liste_achats" class="list-unstyled">  
                        <li class="achat_default" id="default">
                            <span class="achat_qtt" data-achat-qtt="0">0</span> x 
                            <span class="achat_libelle">Aucun produit</span>
                            
                            <input type="button" value="-" data-diff="-1" class="achat_btn_qtt achat_btn_plus btn btn-primary btn-lg">
                            
                            <input type="button" value="+" data-diff="1" class="achat_btn_qtt achat_btn_moins btn btn-primary btn-lg" >
                             
                            <span class="achat_total" data-valeur="0">0</span>
                            <br>
                            <span class="gratuite" style="margin-left: 10px;">
                                <?php echo Form::checkbox(array(
                                    'id'       => null,       
                                    'name'     => null,       
                                    'value'    => 1,
                                    'class'    => 'free'    
                                )); ?>
                                <span>Gratuité</span>
                            </span>
                            <?php echo Form::hidden(array(
                                'name'     => null,
                                'value'    => 0,
                                'class'    => 'quantity'
                            )); ?>
                        </li>  	
                    </ul>
                    <div id="total_panier">
                        <h3>Total</h3>
                        <div>
                            <span id="total" data-valeur="0">0</span>€
                        </div>
                    </div>
                    <div id="monnaie_display">
                        <?php echo Form::input(array(
                            'id'         => 'monnaie',
                            'name'       => 'monnaie', 
                            'class'      => 'form-control',
                            'placeholder' => 'Réglé'
                        )); ?>
                        <div>
                            Rendu : <span id="monnaie_rendu">0</span>€
                        </div>
                    </div>
                    <?php echo Form::submit(array(
                        'id'        => 'submit',
                        'name'      => 'submit',
                        'value'     => 'Valider et payer',
                        'class'     => 'btn btn-success btn-lg',
                    )); ?>
<!--                </form>-->
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
<!--                    Le contenu du panier est déplacé ici-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?php echo Form::close(); ?>