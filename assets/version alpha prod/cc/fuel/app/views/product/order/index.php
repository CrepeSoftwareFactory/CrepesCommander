<div>
    <div class="row">
        <?php if ($stations) { ?>
            <?php 
            $i = 1;
            foreach ($stations as $station) { ?>
            <div class="col-md-3" id="liste_poste_<?php echo $i; ?>">
                <?php echo Form::hidden(array(
                    'class'     => 'station_id',    
                    'name'      => 'station_id[]',    
                    'value'     => $station->get_id(),    
                )); ?>
                <span class="label label-poste"><?php echo $station->name; ?></span>
                
                <ul class="liste_poste list-unstyled panel panel-default">
                    <li class="btn btn-default btn-lg btn-block proco_pile_top">
                        <?php echo Html::anchor('product/order/cook/'.$station->get_id(), $station->get_cooking_product() ?: 'Vide !'); ?>
                    </li>
                    <?php if ($station->get_waiting_products()) { ?>
                        <?php foreach ($station->get_waiting_products() as $product) { ?>
                            <li class="panel-body proco_pile_waiting"><?php echo $product; ?></span></li>
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
        <div class="row">
            <div class="well" >
                <ul class="list-inline">
                    <?php if ($alone_products) { ?>
                        <?php foreach ($alone_products as $product) { ?>
                            <li><?php echo $product; ?></li>
                        <?php } ?>  
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>