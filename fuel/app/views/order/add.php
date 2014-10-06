<?php echo Form::open(array(
    'action'        => '',
    'name'          => 'order'
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
                            )); ?>
                        </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>

        <div class="col-xs-3 bg-info">
            <div id="panier">
                <form class="form-horizontal" role="form">
                    <h4><?php echo Form::label('Nom ! : ', 'lastname'); ?></h4>
                    <div>    
                        <?php echo Form::input(array(
                            'id'         => 'lastname',
                            'name'       => 'lastname', 
                            'value'      => $customer->lastname,
                            'class'      => 'form-control'
                        )); ?>
                    </div>
                    <h4>Panier</h4>
                    <ul id="liste_achats" class="list-unstyled">  
                        <li class="achat_default" id="default">
                            <span class="achat_libelle">Aucun produit n'est renseigné</span>
                            <input type="button" value="-" data-diff="-1" class="achat_btn_qtt achat_btn_plus btn btn-primary">
                            <span class="achat_qtt" data-achat-qtt="0">0</span> 
                            <input type="button" value="+" data-diff="1" class="achat_btn_qtt achat_btn_moins btn btn-primary" >
                            = 
                            <span class="achat_total" data-valeur="0">0</span>
                            <span style="margin-left: 10px;">
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
                    <div>
                        <h3>Total</h3>
                        <span id="total" data-valeur="0">0</span>€
                    </div>
                    
                    <?php echo Form::submit(array(
                        'id'        => 'submit',
                        'name'      => 'submit',
                        'value'     => 'Valider et payer',
                        'class'     => 'btn btn-success btn-lg',
                    )); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>