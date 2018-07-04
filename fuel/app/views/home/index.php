<div style="text-align: center; padding-top: 80px;">
    <?php 
    $maPile = Session::get('maPile');
    echo '<h2 id="currentPileTitle">';
    if($maPile) {     
        ?>Pile actuellement attribuée : <?php 
        echo $maPile;
    }
    echo '</h2>';
    ?><label for="maPile">S'attribuer une pile : </label>
    <br />
    <select name="maPile" id="maPile" class="selectpicker selectpiles" title="Choisir des stations spécifiques à afficher...">
        <?php 
        foreach($stations as $station){
            $selected=""; 
            if($station->station_id==$maPile){
                $selected ="selected";
            }
            echo '<option value="'.$station->station_id.'" '.$station->station_id.' ' . $selected . ' >'.$station->name.'</option>';
        }
        ?>
    </select>
    <?php 

    echo '<button class="btn" id="emptyPile">Enlever l\'attribution</button>';
    echo '<br />';
    echo '<br />';
    echo Html::anchor('order/add', 'J\'ai faim...', array('class' => 'btn btn-primary btn-lg')); 
    echo '<br />';
    echo '<br />';
    echo Html::anchor('product/order', 'Afficher toutes les piles', array('class' => 'btn btn-success btn-lg')); 
    echo '<br />';
    echo '<br />';
    for($i=0; $i<count($stations); $i+=2){
        echo Form::open(array('action' => 'product/order', 'method' => 'get', 'class' => 'display-inline'), array('p[]' => $stations[$i]->station_id));
        if(!empty($stations[$i+1])){
            echo Form::hidden('p[]', $stations[$i+1]->station_id);
            echo Form::submit('submit', 'Piles '.$stations[$i]->station_id .' et '. $stations[$i+1]->station_id.'', array('class' => 'btn btn-warning'));
        }
        else{
            echo Form::submit('submit', 'Pile '.$stations[$i]->station_id .'', array('class' => 'btn btn-warning'));
        }
        echo Form::close();
    }
    echo '<br />';
    echo '<br />';
    echo Form::open(array('action' => 'product/order', 'method' => 'get'));
    ?>
    <select name="p[]" class="selectpicker selectpiles" multiple title="Choisir des stations spécifiques à afficher...">
        <?php foreach($stations as $station){
            echo '<option value="'.$station->station_id.'">'.$station->name.'</option>';
    } 
        ?>
    </select>
    <?php 
    echo  Form::submit('submit', 'Go Piles Spécifiques', array('class' => 'btn btn-info btn-lg'));
    echo Form::close(); 
    ?>
    
</div>