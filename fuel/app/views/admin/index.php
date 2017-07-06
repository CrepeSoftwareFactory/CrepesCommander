<div style="text-align: center; padding-top: 10px;">
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
    <br />
    <h2><button class="btn btn-info" data-toggle="collapse" data-target="#tableAffichageTypesProduits" aria-expanded="false" aria-controls="collapseExample">Déplier</button> Les Types de Produits : <button type="button" class="btn btn-primary" id="addTypeProduct">Ajouter un Type de produit</button></h2>
    <div id="tableAffichageTypesProduits" class="collapse">
        <table class="table table-bordered table-hover tableTypes">
            <tr style="background-color: #FFF;">
                <th>ID</th>
                <th>NOM</th>
                <th>COULEUR</th>
                <th>SUPPRIMER</th>
            </tr>
            <?php 
                foreach($types as $type){
                    echo '<tr id="type'.$type->type_id.'" style="background-color: '.$type->type_couleur.';">';
                        echo '<td>'.$type->type_id.'</td>';
                        echo '<td><input type="text" name="type_label" value="'.$type->type_label.'" /><button data-id="'.$type->type_id.'" data-fct="changeTypeProduct" class="btn btn-success">Ok</button></td>';
                        echo '<td><input type="text" name="type_couleur" value="'.$type->type_couleur.'" /><button data-id="'.$type->type_id.'" data-fct="changeTypeProduct" class="btn btn-success">Ok</button></td>';
                        echo '<td><button type="button" class="btn btn-danger" data-fct="supTypeProduct" data-id="'.$type->type_id.'">Supprimer ce Type</button></td>';
                    echo '</tr>';
                } 
             ?>
        </table>
    </div>
    <br />
    <h2><button class="btn btn-info" data-toggle="collapse" data-target="#tableAffichageProduits" aria-expanded="false" aria-controls="collapseExample">Déplier</button> Les Produits : <button type="button" class="btn btn-primary" id="addProduct">Ajouter un Produit</button></h2>
    <div id="tableAffichageProduits" class="collapse">
        <table class="table table-bordered table-hover tableProduits table-striped">
            <tr style="background-color: #FFF;">
                <th>ID</th>
                <th>CODE</th>
                <th>NOM</th>
                <th>PRIX</th>
                <th>TYPE</th>
                <th>SUPPRIMER</th>
            </tr>
            <?php 
                foreach($products as $product){
                    echo '<tr id="type'.$product->product_id.'" style="background-color: #FFF;">';
                        echo '<td>'.$product->product_id.'</td>';
                        echo '<td><input type="text" name="code" value="'.$product->code.'" /><button data-id="'.$product->product_id.'" data-fct="changeProduct" class="btn btn-success">Ok</button></td>';
                        echo '<td><input type="text" name="name" value="'.$product->name.'" /><button data-id="'.$product->product_id.'" data-fct="changeProduct" class="btn btn-success">Ok</button></td>';
                        echo '<td><input type="text" name="price" value="'.$product->price.'" /><button data-id="'.$product->product_id.'" data-fct="changeProduct" class="btn btn-success">Ok</button></td>';
                        echo '<td><select data-id="'.$product->product_id.'" data-fct="changeProduct" class="form-control">';
                        foreach($types as $type){
                            $isSelected = ($type->type_id==$product->type)? 'selected':'';
                            echo '<option value="'.$type->type_id.'" '.$isSelected.'>'.$type->type_label.'</option>';
                        }
                        echo '</select></td>';
                        echo '<td><button type="button" class="btn btn-danger" data-fct="supProduct" data-id="'.$product->product_id.'">Supprimer ce Produit</button></td>';
                    echo '</tr>';
                } 
             ?>
        </table>
    </div>
    <br />
    <br />
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
<!--                    Le contenu du formulaire est déplacé ici-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>