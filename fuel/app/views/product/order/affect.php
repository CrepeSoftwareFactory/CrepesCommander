
    <div>
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td class="col1">Nom</td>
                        <td class="col2">Produit</td>
                        <td class="col3">Heure</td>
                        <td class="col4">Poste</td>
                        <td class="col5">Statut</td>
                        <td class="col6">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders) { ?>
                       <?php foreach ($orders as $order) { ?>
                            <tr>
                                <th colspan="5">Commande de <?php echo $order->get_customer()->lastname; ?></th>
                                <td>
                                <!--
                                    <a href="#" class="btn btn-success btn-lg">voir</a>
                                    <a href="#" class="btn btn-success btn-lg">modifier</a>
                                -->
                                    <?php if ($order->is_finished()) { ?>
                                    <?php echo Html::anchor('order/finish/'.$order->get_id(), 'C\'est livré !', array(
                                        'class' => 'btn btn-success btn-lg order-finished', 
                                        'title' => 'Livrer la commande de '.$order->get_customer()->lastname,
                                    )); ?>
                                    <?php } else { ?>
                                    <?php echo Html::anchor('order/cancel/'.$order->get_id(), 'Annuler cmde', array(
                                        'class' => 'btn btn-success btn-lg order-cancel', 
                                        'title' => 'Annuler la commande de '.$order->get_customer()->lastname,
                                    )); ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php foreach ($order->get_products() as $product) { ?>
                                <tr>
                                    <th><?php echo $order->get_customer()->lastname; ?></th>
                                    <td><?php echo $product->get_product()->name; ?></td>
                                    <td><?php echo date('H\hi', strtotime($order->date)); ?></td>
                                    <td>
                                        <?php echo Html::anchor('product/order/affect/'.$product->get_id(), 'Pile', array(
                                            'class' => 'btn btn-primary btn-lg color-pile '.(!$product->station_id ? ' active ' : ' '),
                                            'data-station'  => '0',
                                            'data-product'  => $product->get_id(),
                                            'data-order'    => $order->get_id(),
                                        )); ?>
                                        <?php 
                                        $i = 1;
                                        foreach ($this->stations as $station) { ?>
                                            <?php echo Html::anchor('product/order/affect/'.$product->get_id().'/'.$station->get_id(), $station->name, array(
                                                'class' => 'btn btn-primary btn-lg color-poste-'.$i.($product->station_id == $station->get_id() ? ' active ' : ' '),
                                                'data-station'  => $station->get_id(),
                                                'data-product'  => $product->get_id(),
                                            )); ?>
                                        <?php 
                                            $i++;
                                        } ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if(!$product->is_cooked()){
                                        ?>
                                        <div class="dropdown modif_status">
                                            <button class="btn btn-default dropdown-toggle" data-status='<?php echo $product->get_status()->proco_status_id ?>' type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <?php echo $product->get_status()->name; ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <?php
                                                foreach ($this->statuses as $status)
                                                {
                                                    echo '<li><a href="#" data-status='.$status->proco_status_id.' data-idproduct='.$product->get_id().' >'.$status->name.'</a></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                        }
                                        else{
                                             echo '<a href="#" class="btn btn-primary btn-lg disabled" role="button">Terminé</a>';
                                        }
                                        ?>
                                    </td>
                                    <td>
            <!--                        <a href="#" class="btn btn-success btn-lg">voir</a>
                                        <a href="#" class="btn btn-success btn-lg">modifier</a>-->
                                        <?php echo Html::anchor('product/order/delete/'.$product->get_id(), 'Supprimer', array(
                                            'class' => 'btn btn-success btn-lg product-delete',
                                            'title' => 'Supprimer '.$product,
                                        )); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
                
            </table>
        </div>        
    </div>