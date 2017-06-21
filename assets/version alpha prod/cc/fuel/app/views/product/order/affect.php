
    <div>
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td class="col1">Nom</td>
                        <td class="col2">Produit</td>
                        <td class="col3">Heure</td>
                        <td class="col4">Poste</td>
                        <td class="col5">Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders) { ?>
                       <?php foreach ($orders as $order) { ?>
                            <tr>
                                <th colspan="4">Commande de <?php echo $order->get_customer()->lastname; ?></th>
                                <td>
                                <!--
                                    <a href="#" class="btn btn-success btn-lg">voir</a>
                                    <a href="#" class="btn btn-success btn-lg">modifier</a>
                                -->
                                    <?php if ($order->is_finished()) { ?>
                                    <?php echo Html::anchor('order/finish/'.$order->get_id(), 'C\'est livré !', array(
                                        'class' => 'btn btn-success btn-lg order-finished', 
                                        'title' => 'Commande de '.$order->get_customer()->lastname.' livrée',
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
                                            'class' => 'btn btn-primary btn-lg color-pile '.(!$product->station_id ? ' active ' : ' ')
                                        )); ?>
                                        <?php 
                                        $i = 1;
                                        foreach ($this->stations as $station) { ?>
                                            <?php echo Html::anchor('product/order/affect/'.$product->get_id().'/'.$station->get_id(), $station->name, array(
                                                'class' => 'btn btn-primary btn-lg color-poste-'.$i.($product->station_id == $station->get_id() ? ' active ' : ' ')
                                            )); ?>
                                        <?php 
                                            $i++;
                                        } ?>
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