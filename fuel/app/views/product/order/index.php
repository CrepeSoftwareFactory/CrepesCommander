<div>
    <div class="row">
        <?php if ($stations) { ?>
            <?php 
            $i = 1;
            foreach ($stations as $station) { ?>
            <div class="col-sm-6 col-md-6" id="liste_poste_<?php echo $i; ?>">
                <?php echo Form::hidden(array(
                    'class'     => 'station_id',    
                    'name'      => 'station_id[]',    
                    'value'     => $station->get_id(),    
                )); ?>
                <span class="label label-poste"><?php echo $station->name; ?></span>
                <?php $cookedProduct = $station->get_cooking_product();
                //print_r($cookedProduct);
                ?>
                <ul class="liste_poste list-unstyled panel panel-default">
                    <li class="btn btn-default btn-lg btn-block proco_pile_top" id="<?php if($cookedProduct){echo $cookedProduct->product_order_id;} ?>">
                        <?php 
                            if($cookedProduct['comment']!==null) { 
                                $icone_commentaire = '<a href="#"><span class="glyphicon glyphicon-comment"></span></a>';
                            }else{
                                $icone_commentaire = '';
                            }
                            $produit = Html::anchor('product/order/cook/'.$station->get_id(), $cookedProduct ?: 'Vide !', array('class' => 'cook notSelectable')) . $icone_commentaire;
                            echo $produit;
                            
                        ?>
                    </li>
                    <?php if ($station->get_waiting_products()) { ?>
                        <?php foreach ($station->get_waiting_products() as $product) { ?>
                            <li class="panel-body notSelectable proco_pile_waiting status_<?php echo $product->status; ?>"  id="<?php echo $product->product_order_id; ?>"><?php echo $product; ?></span></li>
                        <?php } ?>
                    <?php } else { ?>
                            <li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>
                    <?php } ?>
                </ul>
            </div>
            <?php 
                $i++;
            } ?>
        <?php } ?>
        </div>
        <div class="row alone_products">
            <?php foreach (Model_Product::$types as $key => $type) { ?>
                <div class="col-md-4">
                    <h3><?php echo $type; ?></h3>
                    <ul class="colonne_pile">
                        <?php if ($alone_products) { ?>
                            <?php foreach ($alone_products as $product) { ?>
                                <?php if ($product->get_product()->type == $key) { ?>
                                    <li class="notSelectable status_<?php echo $product->status; ?>" id="<?php echo $product->product_order_id; ?>"><?php echo $product; ?></li>
                                <?php } ?>
                            <?php } ?>  
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            
            
        </div>
    </div>

    <div id="myModal" class="modal fade">
        <input type="hidden" name="hadToRefresh" class="hadToRefresh" value="0">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                   commentaires de la commande !
                </div>
                <div class="modal-footer">
                    <div class="dropdown modif_pile">
                        <?php
                        $stations = Model_Station::find();
                        ?>
                        <button class="btn btn-default dropdown-toggle" data-status='' type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><button class="linkStatus btn btn-primary btn-lg color-pile" data-pile="0" data-idproduct="" >PILE</button></li>
                            <?php
                            foreach ($stations as $station)
                            {
                                echo '<li><button class="linkStatus btn btn-primary btn-lg color-poste-'.$station->station_id.'" data-pile='.$station->station_id.' data-idproduct= >'.$station->name.'</button></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="dropdown modif_status">
                        <button class="btn btn-default dropdown-toggle" data-status='' type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <?php
                            $statuses = Model_Proco_Status::find();
                            foreach ($statuses as $status)
                            {
                                echo '<li><button class="linkStatus btn btn-primary btn-lg color-status-'.$status->proco_status_id.'" data-status='.$status->proco_status_id.' data-idproduct= >'.$status->name.'</button></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    


