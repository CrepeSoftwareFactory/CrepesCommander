<?php 
    $maPile = Session::get('maPile');
    if(!empty($maPile)){
        ?>
            <input type="hidden" name="hadToRefresh" class="hadToRefresh" value="0">
            <div class="liste_proco_client">
                <h2>Pile attribuée actuellement : <?php echo $maPile ?></h2>
                <div class="row">
                    <table class="table" >
                        <thead>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    Commande de
                                </td>
                                <td>
                                    Pile
                                </td>
                                <td>
                                    Produit
                                </td>
                                <td>
                                    Heure
                                </td>
                                <td>
                                    Commentaire
                                </td>
                                <td>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) { 
                            foreach ($orders as $order) { 
                                if($order->get_products()){
                                    $count = count($order->get_products());
                                    $myIncrement = 0;
                                    foreach ($order->get_products() as $product) { 
                                        $myIncrement++;
                                        if($product->station_id != null){
                                            $station_id = "P".$product->station_id;
                                        }else{
                                            $station_id = "?";
                                        }
                                        echo '<tr class="'.$order->order_id.' order-line">';
                                        $classBackground="";
                                        if($product->station_id==$maPile){
                                            $classBackground='class="bg-success"';
                                        }
                                        elseif($product->station_id==null){
                                            $classBackground='class="bg-dark"';
                                        }
                                        else{
                                            $classBackground='class="bg-danger"';
                                        }
                                        if($myIncrement == 1){
                                            echo '<td rowspan='.$count.'>';
                                            echo '&nbsp;<button id="affect'.$order->order_id.'" class="btn btn-info" onclick="setPileToOrder('.$maPile.', '.$order->order_id.')">Je prends</button>';
                                            echo '</td>';
                                            echo '<td rowspan='.$count.'>';
                                            echo $order->get_customer()->lastname;
                                            echo '</td>';
                                        }
                                        echo '<td '. $classBackground.'>';
                                        echo '<span class="p-3 mb-2 font-weight-bold">'.$station_id.'</span>';
                                        echo '</td>';
                                        echo '<td '. $classBackground.'>';
                                        echo $product->get_product()->name;
                                        echo '</td>';
                                        echo '<td '. $classBackground.'>';
                                        echo date('H\hi', strtotime($order->date));
                                        echo '</td>';
                                        echo '<td '. $classBackground.'>';
                                        if($product->get_comment()){
                                            echo $product->get_comment();
                                        }
                                        echo '</td>';
                                        if($myIncrement == 1){
                                            echo '<td rowspan='.$count.'>';
                                            echo '&nbsp;<button id="finish'.$order->order_id.'" class="btn btn-info" onclick="setFinishOrder('.$order->order_id.')">C\'est livré !</button>';
                                            echo '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                            }
                        }
                        else
                        {
                        ?>
                            <tr>
                                <th>Pas de produits commandés</th>
                            </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        <?php
    }
    else{
        echo 'Veuillez vous attribuer une pile pour accéder aux différentes commandes : ';
        echo '<label for="maPile">S\'attribuer une pile : </label>';
        echo '<select name="maPile" id="maPile" class="selectpicker selectpiles" title="Choisir des stations spécifiques à afficher...">';
            foreach($stations as $station){
                echo '<option value="'.$station->station_id.'">'.$station->name.'</option>';
            }
        echo '</select>';
    }
?>
