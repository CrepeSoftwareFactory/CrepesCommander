
    <div>
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td class="col1">Nom</td>
                        <td class="col2">Produit</td>
                        <td class="col3">Heure</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders) { ?>
                       <?php foreach ($orders as $order) { ?>
                            <tr>
                                <th colspan="3">
                                    Commande de <?php echo $order->get_customer()->lastname; ?>
                                    <span style="color:red"> - <?php echo Model_Order::$status[$order->status]; ?></span>
                                </th>
                            </tr>
                            <?php foreach ($order->get_products() as $product) { ?>
                                <tr>
                                    <th><?php echo $order->get_customer()->lastname; ?></th>
                                    <td><?php echo $product->get_product()->name; ?></td>
                                    <td><?php echo date('H\hi', strtotime($order->date)); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
                
            </table>
        </div>        
    </div>