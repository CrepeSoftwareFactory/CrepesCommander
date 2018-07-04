<?php 
    $maPile = Session::get('maPile');
    if(!empty($maPile)){
        ?>
            <div class="liste_proco_client">
                <h2>Pile attribuée actuellement : <?php echo $maPile ?></h2>
                <div class="row">
                    <table class="table" >
                        <thead>
                            <tr>
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
                                        if($product->station_id==$maPile){
                                            echo '<tr class="bg-success">';
                                        }
                                        elseif($product->station_id==null){
                                            echo '<tr class="bg-dark">';
                                        }
                                        else{
                                            echo '<tr class="bg-danger">';
                                        }
                                            if($myIncrement == 1){
                                                echo '<td rowspan='.$count.'>';
                                                echo $order->get_customer()->lastname;
                                                echo '&nbsp;<button class="btn btn-info">Affectation</button>';
                                                echo '&nbsp;<button class="btn btn-info">Livraison</button>';
                                                echo '</td>';
                                            }
                                            echo '<td>';
                                            echo '<span class="p-3 mb-2 font-weight-bold">'.$station_id.'</span>';
                                            echo '</td>';
                                            echo '<td>';
                                            echo $product->get_product()->name;
                                            echo '</td>';
                                            echo '<td>';
                                            echo date('H\hi', strtotime($order->date));
                                            echo '</td>';
                                            echo '<td>';
                                            if($product->get_comment()){
                                                echo $product->get_comment();
                                            }
                                            echo '</td>';
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
