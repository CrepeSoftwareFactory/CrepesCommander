<table>
    <?php if ($orders) { ?>
        <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo $order->get_customer()->lastname; ?></td>
            </tr>
            <?php if (($order->get_products())) { ?>
                <?php foreach ($order->get_products() as $product) { ?>
                <tr>
                    <td><?php echo $product->get_product()->name; ?></td>
                    <td>
                        <?php foreach ($stations as $station) { ?>
                            <?php echo $station->name;  ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</table>
