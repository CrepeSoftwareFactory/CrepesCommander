<div class="panel panel-default">
    <div class="panel-body" id="rapport">
        <div>
            <h3>Statistiques générales : </h3>
           
            <?php 
                echo 'Commandes livrées : '.$count_delivered.' - ';
                echo ' <b>Ventes : '.$price.' euros'.' </b>- '; 
                echo ' Infos : raspiCC3544'; 
            ?>
        </div>
        <div>
            <h3>Produits en attente : </h3>
            <?php 
                $nbWaitingTT = 0;
                foreach($types as $type){
                    echo $type->type_label.' : ';
                    $nbWaiting = $type->get_nbWaiting();
                    $nbWaitingTT += intval($nbWaiting);
                    echo $nbWaiting;
                    echo '<br />';
                } 
                echo 'Total : '.$nbWaitingTT;
            ?>
        </div>
        <div>
            <h3>Produits délivrés : </h3>
            <?php 
                $nbDeliveredTT = 0;
                $tab = $type->get_productMoy();
                foreach($types as $type){
                    $inc=0;
                    echo $type->type_label.' : ';
                    $nbDelivered = $type->get_nbDelivered();
                    $nbDeliveredTT += intval($nbDelivered);
                    echo $nbDelivered;
                    if(isset($tab[$type->type_id])){
                        $moy = $tab[$type->type_id]['sum']/$tab[$type->type_id]['inc'];
                        echo '<br />';
                        echo 'Temps moyen passé sur ce type de produit : '.floor($moy/60).' minutes et '.round($moy%60);
                    }
                    echo '<br />';
                } 
                echo 'Total : '.$nbDeliveredTT;
            ?>
        </div>
        <div>
            <h3>Produits cuisinés et non livrés : </h3>
            <?php 
                $nbCookedTT = 0;
                foreach($types as $type){
                    echo $type->type_label.' : ';
                    $nbCooked = $type->get_nbCooked();
                    $nbCookedTT += intval($nbCooked);
                    echo $nbCooked;
                    echo '<br />';
                }
                echo 'Total : '.$nbCookedTT;
            ?>
        </div>
    </div>
</div>