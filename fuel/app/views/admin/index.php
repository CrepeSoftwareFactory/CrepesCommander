<div style="text-align: center; padding-top: 80px;">
    <label for="nbPiles">Changer le nombre de piles/stations :</label>
    <select name="nbPiles" class="selectpicker show-tick nbPiles">
        <?php 
        for($i=1; $i<=10;$i++){
            echo '<option';
            echo ($i==count($stations))? ' selected':'';
            echo '>'.$i.'</option>';
        }
        ?>
    </select>
</div>