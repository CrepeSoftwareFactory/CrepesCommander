<?php 
    $maPile = Session::get('maPile');
    if(!empty($maPile)){
        ?>
            <div class="liste_proco_client">
                <h2>Pile actuelle <?php echo $maPile ?></h2>
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
                        <tbody></tbody>
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
