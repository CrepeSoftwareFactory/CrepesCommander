
    <div class="liste_proco_client">
        <div class="row">
            <table class="table table-striped" >
                <thead>
                    <tr>
                        <td class="col1">Nom</td>
                        <td class="col2">Produit</td>
                        <td class="col3">Heure</td>
                        <td class="col4">Poste</td>
                        <td class="col5">Statut</td>
                        <td class="col5b">Commentaire</td>
                        <td class="col6">Actions</td>
                    </tr>
                </thead>
                
                    <?php if ($orders) { ?>
                       <?php foreach ($orders as $order) { ?>
                            <tr>
                                <th colspan="6"><button class="btn btn-info" data-toggle="collapse" data-target="#collapse-<?php echo $order->get_customer()->customer_id; ?>" aria-expanded="false" aria-controls="collapseExample" >Commande de <?php echo $order->get_customer()->lastname; ?></button></th>
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
                            <tbody class="collapse" id="collapse-<?php echo $order->get_customer()->customer_id; ?>">
                            <?php
                                if($order->get_products()){
                                foreach ($order->get_products() as $product) { ?>
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
                                                    echo '<li><button class="btn btn-primary btn-lg color-status-'.$status->proco_status_id.'" data-status='.$status->proco_status_id.' data-idproduct='.$product->get_id().' >'.$status->name.'</button></li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                        }
                                        else{
                                             echo '<button class="btn btn-primary btn-lg disabled" role="button">Terminé</button>';
                                             echo '<button data-fct="rempiler" data-idproduct='.$product->get_id().' class="rempiler btn btn-warning btn-lg" role="button">Rempiler</button>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($product->get_comment() != null){
                                            echo '<button class="btn btn-primary btn-lg affichComment"><span class="glyphicon glyphicon-comment"></span></button><input class="commentaire" type="hidden" value="'.$product->get_comment().'"/></td>';
                                        }
                                        ?>
                                    <td>
                                        <!-- 
                                        <a href="#" class="btn btn-success btn-lg">voir</a>
                                        <a href="#" class="btn btn-success btn-lg">modifier</a>
                                        -->
                                        <?php echo Html::anchor('product/order/delete/'.$product->get_id(), 'Supprimer', array(
                                            'class' => 'btn btn-success btn-lg product-delete',
                                            'title' => 'Supprimer '.$product,
                                        )); ?>
                                    </td>
                                </tr>
                            <?php 
                                } 
                            } else{ ?>
                            <tr><th>Pas de produits sur cette commande</th></tr>
                                <?php } ?>
                            </tbody>
                        <?php } ?>
                    <?php } ?>
                
                
            </table>
        </div>
    </div>
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-body">
                    <!-- Le contenu des commentaires est déplacé ici-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->