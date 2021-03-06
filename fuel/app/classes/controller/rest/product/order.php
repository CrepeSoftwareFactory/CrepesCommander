<?php

class Controller_Rest_Product_Order extends Controller_Rest 
{
    //Fonction pour annuler une commande dont les proco ne sont pas terminées
    //In : order.order_id
    //Return : String 
    public function post_cancel()
    {
        //On récupère l'id de la commande de la requête POST
        $order_id = Input::post('order_id');
        try {
            //On cherche la commande avec son id qu'on a récupéré
            $order = Model_Order::find_by_pk($order_id);
            //on lui met un status CANCEL
            $order->status = Model_Order::STATUS_CANCEL;
            //Si la requête ne fonctionne pas on interrompt et on renvoie une erreur
            if (!$order->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            //Si la requête fonctionne on charge dans une array et on renvoie les informations
            $response = array(
                'error'       => false,  
                'message'     => 'Commande annulée',  
            );
            Session::set_flash('success', 'Commande annulée');
        } catch (Exception $ex) {
            //Si une erreur on renvoie le message d'erreur en question
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    
    //Action pour livrer une commande où toutes les proco ont été terminées 
    //In : order.order_id
    //Out : String
    public function post_finish()
    {
        $order_id = Input::post('order_id');
        try {
            $order = Model_Order::find_by_pk($order_id);
            $order->status = Model_Order::STATUS_DELIVERED;
            if (!$order->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            $response = array(
                'error'       => false,  
                'message'     => 'Commande livrée !',  
            );
            Session::set_flash('success', 'Commande livrée !');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    
    //Fonction qui va supprimer une proco d'une commande
    //In : product_order.product_order.id
    //Out : String
    public function post_delete() 
    {
        //On récupère l'id du produit qu'on veut supprimer à partir d'une requête POST
        $product_id = Input::post('product_id');
        try 
        {
            $product = Model_Product_Order::find_by_pk($product_id);
            if (!$product->delete()) {
                throw new Exception('Erreur lors de la suppression');
            }
            else
            {
                //On recupère la commande si il n'y a plus de proco associée, on la met dans la variable alone
                $alone = Model_Order::get_alone();
                if($alone[0]){
                    //si cette commande seule existe, on la supprime après avoir récupéré son id
                    $commande_id = $alone[0]->order_id;
                    $commande = Model_Order::find_by_pk($commande_id);
                    if (!$commande->delete())
                    {
                        throw new Exception('Erreur lors de la suppression');
                    }
                    else
                    {
                        $message = 'La proco et La commande ont été bien supprimé';
                        $delete_commande = true;
                    }
                }
                else
                {
                    $message = 'La proco a bien été supprimé';
                    $delete_commande = false;
                }
                
                DB::commit_transaction();
                $response = array(
                        'error'             => false,  
                        'message'           => $message, 
                        'delete_commande'   => $delete_commande,
                );
            }
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    //Fonction pour récupérer la valeur de statut et de pile d'une proco
    public function post_status()
    {
        $product_id = Input::post('id');
        if ($product_id) {
             try {
                $product = Model_Product_Order::find_by_pk($product_id);
                $product_info = $product->get_product();
                $product_order = $product->get_order();
                $product_customer = $product_order->get_customer();
                 
                
                $status = Model_Proco_Status::find_by_pk($product->status);
                $comment = $product->comment;
                
                $pile = $product->get_station();
                 
                 if($pile==NULL){
                     $pile = "PILE";
                 }
                
                $response = array(
                    'error'     => false,  
                    'message'   => $status,  
                    'comment'   => $comment,  
                    'pile'      => $pile,
                    'title'    => '<h3>'.$product_info->name.' - '.$product_customer->lastname.'</h3>',
                );
            } catch (Exception $ex) {
                $response = array(
                    'error'       => true,  
                    'message'     => $ex->getMessage(),  
                );
            }
            
            return $this->response($response);
         }
    }
    
    //Fonction pour modifier la pile d'un proco
    public function post_affect()
    {
        $product_id = Input::post('product_id');
        $station_id = Input::post('station_id');
        
        if ($product_id) {
            try {
                $product = Model_Product_Order::find_by_pk($product_id);
                if(empty($station_id)){
                    $station_id=NULL;
                }
                $product->station_id = $station_id;
                if (!$product->save()) {
                    throw new Exception($product->validation()->show_errors());
                }
                
                if($station_id==NULL){
                    $station_id=0;
                }
                $response = array(
                    'error'       => false,  
                    'message'     => $station_id,  
                );
            } catch (Exception $ex) {
                $response = array(
                    'error'       => true,  
                    'message'     => $ex->getMessage(),  
                );
            }
            
            return $this->response($response);
        }
    }

    //Fonction pour s'attribuer une pile
    public function post_setPile() 
    {
        try{
            $maPile = Input::post('maPile');
            // store the pile of user in session
            Session::set('maPile', $maPile);
            if($maPile){
                $message = 'Pile actuelle : '+$maPile;
            }
            else{
                $message = 'Pas de piles attribuées';
            }
            $response = array(
                'error'       => false,  
                'message'     => $message,
                'value'       => $maPile  
              );
            return $this->response($response);
        }catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
    }

    // Fonction pour récupérer sa pile
    public function post_getPile()
    {
        try{
            // store the pile of user in session
            $maPile = Session::get('maPile');
            if($maPile){
                $message = 'Pile actuelle : '+$maPile;
            }
            else{
                $message = 'Pas de piles attribuées';
            }
            $response = array(
                'error'       => false,  
                'message'     => $message,
                'value'       => $maPile  
              );
            return $this->response($response);
        }catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
            return $this->response($response);
        }
    }

    // Fonction pour vider sa pile
    public function post_emptyPile() 
    {
        try{
            Session::delete('maPile');
            $response = array(
                'error'       => false,  
                'message'     => "Pile vidé",  
              );
        }catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    //Fonction pour renvoyer les commentaires sur la page 'au boulot' via appel Ajax
    public function post_comment()
    {
        try{
            DB::start_transaction();
            $station_id = Input::post('id');
            $station = Model_Station::find_by_pk($station_id);
            $cooking_product = $station->get_cooking_product();
            
            return $this->response($cooking_product);
            
        }catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
    }
    
    //Derniers produits cusinés
    public function post_refresh_lastCooked(){
       try
        {
            DB::start_transaction();
            $station_id = Input::post('id');
            $station = Model_Station::find_by_pk($station_id);
            $last_cooked_product = $station->get_last_cooked_product();
            
            if($last_cooked_product){
                $message = $last_cooked_product.' <button data-idProCo="'.$last_cooked_product->product_order_id.'" class="rempiler btn btn-danger btn-xs">Renvoyer en Pile</button>';
            }
            else{
                $message = 'Aucun';
            }
            
            DB::commit_transaction();
           
            $response = array(
                'error'         => false,  
                'message'       => $message,
            );
        }
        catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_refresh_unaffected(){
        try
        {
            DB::start_transaction();
            $return_alone_product = '';
            $unaffected_products = Model_Product_Order::get_unaffected();
            $types = Model_Product_Type::find();
            foreach ($types as $type) { 
                $return_alone_product .= '<div class="col-md-4"><h3>'.$type->type_label.'</h3><ul class="colonne_pile">';
                if ($unaffected_products) { 
                    foreach ($unaffected_products as $product) { 
                         if ($product->get_product()->type == $type->type_id) {
                             $return_alone_product .= '<li  class="status_'.$product->status.'" id="'.$product->product_order_id.'">'.$product.'</li>';
                         } 
                    }   
                }
                $return_alone_product .= '</ul></div>';
            }
            DB::commit_transaction();
            $response = array(
                'error'         => false,  
                'alone_product' => $return_alone_product,
            );
        }
        catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    public function post_refresh(){
        try
        {
            DB::start_transaction();
            $liste_attente = '<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>';
            $station_id = Input::post('id');
            $station = Model_Station::find_by_pk($station_id);
            $cooking_product = $station->get_cooking_product();
            $waiting_products = $station->get_waiting_products();
            
            if($cooking_product){
                $idProduct = $cooking_product->product_order_id;
            }
            else{
                $idProduct = '';
            }
            
            if($cooking_product && $cooking_product['comment']!==null) { 
                $icone_commentaire = '<button class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-comment"></span></button>';
            }else{
                $icone_commentaire = '';
            }
            $produit = Html::anchor('product/order/cook/'.$station->get_id(), $cooking_product ?: 'Vide !', array('class' => 'cook')) . $icone_commentaire;
            
            if(count($waiting_products) > 0){
                $liste_attente = '';
                foreach ($waiting_products as $i => $product) {
                       $liste_attente .= '<li class="panel-body proco_pile_waiting status_'.$product->status.'" id="'.$product->product_order_id.'">'.$product.'</span></li>';
                }
            }
            
            DB::commit_transaction();
           
            $response = array(
                'error'         => false,  
                'message'       => $produit,
                'attente'       => $liste_attente,
                'idProduct'     => $idProduct,
            );
        }
        catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    //Fonction qui renvoie une liste des produits d'un poste après en avoir validé un
    //Renvoi : variable message et product => message et false si pas de produit dans la liste. Sinon contenu html et true 
    public function post_cook()
    {
        try {
            DB::start_transaction();
            $future_product = '';
            $liste_attente = '';
            $station_id = Input::post('id');
            $return_alone_product = '';
            $station = Model_Station::find_by_pk($station_id);
           
            $cooking_product = $station->get_cooking_product();
            if ($cooking_product) {
               $cooking_product->end = date('Y-m-d H:i:s');
               if (!$cooking_product->save()) {
                   throw new Exception($cooking_product->validation()->show_errors());
               }
            }
            
            $urgent_product = $station->get_urgent_product();
            $waiting_products = $station->get_waiting_products();
            $unaffected_products = Model_Product_Order::get_unaffected();
            
            if($urgent_product){
                $future_product = $urgent_product[0];
                $urgent_product[0]->start = date('Y-m-d H:i:s');
                $urgent_product[0]->station_id = $station_id;
                if (!$urgent_product[0]->save()) {
                    throw new Exception($waiting_products[0]->validation()->show_errors());
                }
            }
            else if ($waiting_products && $waiting_products[0]->status!=3) {
               $waiting_products[0]->start = date('Y-m-d H:i:s');
               $future_product = $waiting_products[0];
               if (!$waiting_products[0]->save()) {
                   throw new Exception($waiting_products[0]->validation()->show_errors());
               }
               if(count($waiting_products) == 1){
                   $liste_attente = '<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>';
               }
               else{
                   foreach ($waiting_products as $i => $product) {
                       if($i>0){
                           $liste_attente .= '<li class="panel-body proco_pile_waiting status_'.$product->status.'" id="'.$product->product_order_id.'">'.$product.'</span></li>';
                       }
                    }
               }
               
                    
            } else {
                $liste_attente = '<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>';
                
                Log::error(print_r($unaffected_products, true));
                if ($unaffected_products && $unaffected_products[0]->status!=3) {
                    $unaffected_products[0]->start = date('Y-m-d H:i:s');
                    $unaffected_products[0]->station_id = $station_id;
                    $future_product = $unaffected_products[0];
                    if (!$unaffected_products[0]->save()) {
                        throw new Exception($unaffected_products[0]->validation()->show_errors());
                    }
                    unset($unaffected_products[0]);
                }
            }
            $types = Model_Product_Type::find();
            foreach ($types as $type) { 
                $return_alone_product .= '<div class="col-md-4"><h3>'.$type->type_label.'</h3><ul class="colonne_pile">';
                if ($unaffected_products) { 
                    foreach ($unaffected_products as $product) { 
                         if ($product->get_product()->type == $type->type_id) {
                             $return_alone_product .= '<li>'.$product.'</li>';
                         } 
                    }   
                }
                $return_alone_product .= '</ul></div>';
            }

            if($future_product && $future_product['comment']!==null) { 
                $icone_commentaire = '<button class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-comment"></span></button>';
            }else{
                $icone_commentaire = '';
            }
            $produit = Html::anchor('product/order/cook/'.$station->get_id(), $future_product ?: 'Vide !', array( 'class' => 'cook' )) . $icone_commentaire;
            
            DB::commit_transaction();
            
            $idProduct = ($future_product) ? $future_product->product_order_id : '';
           
            $response = array(
                'error'         => false,  
                'message'       => $produit,
                'attente'       => $liste_attente,
                'alone_product' => $return_alone_product,
                'idProduct'     => $idProduct,
                
            );
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
              'error'       => true,  
              'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    
    public function post_rempiler()
    {
        $productId = Input::post('productId');
        try {
            $proco = Model_Product_Order::find_by_pk($productId);
            $proco->start = null;
            $proco->end = null;
            if (!$proco->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            $response = array(
                'error'      => false,  
                'message'    => 'Produit rempilé',
            );
            Session::set_flash('success', 'Priorité changée');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }

    //Fonction pour attribuer toutes les proco d'une commande à une pile
    public function post_changePileOrder()
    {
        $idOrder = Input::post('idOrder');
        $station_id = Input::post('idStation');
        try
        {
            $order = Model_Order::find_by_pk($idOrder);

            if($order->get_products_without_affect())
            {
                foreach ($order->get_products_without_affect() as $product) 
                {
                    $product->station_id = $station_id;
                    if (!$product->save()) 
                    {
                        throw new Exception($product->validation()->show_errors());
                    }
                    
                    $response = array(
                        'error'       => false,  
                        'message'     => $station_id,  
                    );
                }
            }
            else{
                $response = array(
                    'error'       => false,  
                    'message'     => 'Aucun produit disponible sur cette commande ou vous avez peut-être double cliqué sur le bouton',  
                );
            }
        }
        catch (Exception $ex) 
        {
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }

    //Fonction pour livrer toute une commande
    public function post_finishOrder()
    {
        $idOrder = Input::post('idOrder');
        try
        {
            $order = Model_Order::find_by_pk($idOrder);

            if($order->get_products())
            {
                foreach ($order->get_products() as $product) 
                {
                    $product->start = date('Y-m-d H:i:s');
                    $product->end = date('Y-m-d H:i:s');
                    if (!$product->save()) 
                    {
                        throw new Exception($product->validation()->show_errors());
                    }
                }
                $order->status = Model_Order::STATUS_DELIVERED;
                if (!$order->save()) {
                    throw new Exception($order->validation()->show_errors());
                }
                $response = array(
                    'error'       => false,  
                    'message'     => 'Commande livrée !',  
                );
                Session::set_flash('success', 'Commande livrée !');
            }
        }
        catch (Exception $ex) 
        {
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        return $this->response($response);
    }
    
    //Fonction qui va modifier le status d'une proco
    //Entrée : idProduct, newStatus
    //Sortie : newStatus
    public function post_change_status() 
    {
        $idProduct = Input::post('idProduct');
        $newStatus = Input::post('newStatus');
        $isTop = Input::post('isTop');
        try {
            $proco = Model_Product_Order::find_by_pk($idProduct);
            $procoStatus = Model_Proco_Status::find_by_pk($newStatus);
            $proco->status = $newStatus;
            if($isTop && $newStatus == 3){
                $proco->start = null;
            }
            if (!$proco->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            $response = array(
                'error'      => false,  
                'message'    => 'Priorité changée',
                'newStatus'  => $procoStatus
            );
            Session::set_flash('success', 'Priorité changée');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }

    //Send view order/list
    public function post_get_view_list()
    {
        try {
            $maPile = Session::get('maPile');
            $orders = Model_Order::find(function($query) {
                $query
                    ->where('status', 'NOT IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                    ->order_by('date', 'ASC')
                ;
            });
            $html="";
            if ($orders) { 
                foreach ($orders as $order) { 
                    if($order->get_products_by_type()){
                        $count = count($order->get_products_by_type());
                        $myIncrement = 0;
                        foreach ($order->get_products_by_type() as $product) { 
                            $myIncrement++;
                            if($product->station_id != null){
                                $station_id = "P".$product->station_id;
                            }else{
                                $station_id = "?";
                            }
                            $html .= '<tr class="'.$order->order_id.' order-line">';
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
                                $html .= '<td rowspan='.$count.'>';
                                $html .= '<b>'.$order->get_customer()->lastname.'</b>';
                                $html .= '<br />';
                                $html .= '&nbsp;<button id="affect'.$order->order_id.'" class="btn btn-info" onclick="setPileToOrder('.$maPile.', '.$order->order_id.')">Je prends</button>';
                                $html .= '</td>';
                            }
                            $html .= '<td '. $classBackground.'>';
                            $html .= '<span class="p-3 mb-2 font-weight-bold">'.$station_id.'</span>';
                            $html .= '</td>';
                            $html .= '<td '. $classBackground.'>';
                            $html .= $product->get_product()->name;
                            $html .= '</td>';
                            $html .= '<td '. $classBackground.'>';
                            $html .= date('H\hi', strtotime($order->date));
                            $html .= '</td>';
                            $html .= '<td '. $classBackground.'>';
                            if($product->get_comment()){
                                $html .= $product->get_comment();
                            }
                            $html .= '</td>';
                            if($myIncrement == 1){
                                $html .= '<td rowspan='.$count.'>';
                                $html .= '&nbsp;<button id="finish'.$order->order_id.'" class="btn btn-info" onclick="setFinishOrder('.$order->order_id.')">C\'est livré !</button>';
                                $html .= '</td>';
                            }
                            $html .= '</tr>';
                        }
                    }
                }
            }
            $response = array(
                'error'      => false,  
                'message'  => $html
            );
        }catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage()
            );
        }
        return $this->response($response);
    }
    
    //Fonction qui va modifier la pile d'une proco
    //Entrée : idProduct, newPile
    //Sortie : newPile
    public function post_change_pile()
    {
        $idProduct = Input::post('idProduct');
        $newPile = Input::post('newPile');
        try
        {
            $proco = Model_Product_Order::find_by_pk($idProduct);
            if($newPile==0){$newPile=NULL;}
            if($proco->start!==NULL && $newPile !== null || $newPile !== "0"){$proco->start=NULL;}
            $proco->station_id = $newPile;
            if (!$proco->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            $procoPile = $proco->get_station();
             $response = array(
                'error'      => false,  
                'message'    => 'pile changée',
                'newPile'  => $procoPile
            );
            Session::set_flash('success', 'Pile changée');
        } catch (Exception $ex) {
            DB::rollback_transaction();
            $response = array(
                'error'       => true,  
                'message'     => $ex->getMessage(),  
            );
        }
        
        return $this->response($response);
    }
    
    public function post_refresh_pending_orders()
    {
        $html='';
        $this->stations = Model_Station::find();
        $orders = Model_Order::find(function($query) {
            $query
                ->where('status', 'NOT IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                ->order_by('date', 'DESC')
            ;
        });
        $this->statuses = Model_Proco_Status::find();
        
       
        $html .= '<div class="row"><table class="table table-striped"><thead><tr><td class="col1">Nom</td><td class="col2">Produit</td><td class="col3">Heure</td><td class="col4">Poste</td><td class="col5">Statut</td><td class="col5b">Commentaire</td><td class="col6">Actions</td></tr></thead>';
        if ($orders) {
            foreach ($orders as $order) {
                $html .= '<tr><th colspan="6"><button class="btn btn-info" data-toggle="collapse" data-target="#collapse-'.$order->get_customer()->customer_id.'" aria-expanded="false" aria-controls="collapseExample" >Commande de '.$order->get_customer()->lastname.'</button></th><td>';
                if ($order->is_finished()) {
                $html .= Html::anchor('order/finish/'.$order->get_id(), 'C\'est livré !', array(
                                                    'class' => 'btn btn-success btn-lg order-finished', 
                                                    'title' => 'Livrer la commande de '.$order->get_customer()->lastname,
                                                ));
                } else {
                $html .= Html::anchor('order/cancel/'.$order->get_id(), 'Annuler cmde', array(
                                                    'class' => 'btn btn-success btn-lg order-cancel', 
                                                    'title' => 'Annuler la commande de '.$order->get_customer()->lastname,
                                                ));
                }
                $html .= '</td></tr><tbody class="collapse" id="collapse-'.$order->get_customer()->customer_id.'">';
                if($order->get_products()){
                    foreach ($order->get_products() as $product) {
                        $html .= '<tr><th>'.$order->get_customer()->lastname.'</th><td>'.$product->get_product()->name.'</td><td>'.date('H\hi', strtotime($order->date)).'</td><td>'. Html::anchor('product/order/affect/'.$product->get_id(), 'Pile', array(
                                                            'class' => 'btn btn-primary btn-lg color-pile '.(!$product->station_id ? ' active ' : ' '),
                                                            'data-station'  => '0',
                                                            'data-product'  => $product->get_id(),
                                                            'data-order'    => $order->get_id(),
                                                        ));
                        $i = 1;
                        foreach ($this->stations as $station) { 
                        $html .=  ' '.Html::anchor('product/order/affect/'.$product->get_id().'/'.$station->get_id(), $station->name, array(
                                                                    'class' => 'btn btn-primary btn-lg color-poste-'.$i.($product->station_id == $station->get_id() ? ' active ' : ' '),
                                                                    'data-station'  => $station->get_id(),
                                                                    'data-product'  => $product->get_id(),
                                                                )); 
                        $i++;
                        }
                        $html .= '</td><td>';
                        if(!$product->is_cooked()){
                            $html .= '<div class="dropdown modif_status"><button class="btn btn-default dropdown-toggle" data-status='.$product->get_status()->proco_status_id.' type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'.$product->get_status()->name.' <span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                            foreach ($this->statuses as $status)
                            {
                            $html .= '<li><button class="btn btn-primary btn-lg color-status-'.$status->proco_status_id.'" data-status='.$status->proco_status_id.' data-idproduct='.$product->get_id().' >'.$status->name.'</button></li>';
                            }
                            $html .= '</ul></div>';
                        }
                        else
                        {
                            $html .= '<button class="btn btn-primary btn-lg disabled" role="button">Terminé</button>';
                            $html .= '<button data-fct="rempiler" data-idproduct='.$product->get_id().' class="rempiler btn btn-warning btn-lg" role="button">Rempiler</button>';
                        }
                        $html .= '</td><td>';
                        if($product->get_comment() != null){
                            $html .= '<button class="btn btn-primary btn-lg affichComment"><span class="glyphicon glyphicon-comment"></span></button><input class="commentaire" type="hidden" value="'.$product->get_comment().'"/>';
                        }
                        $html .= '</td><td>';
                        $html .= Html::anchor('product/order/delete/'.$product->get_id(), 'Supprimer', array(
                                                                'class' => 'btn btn-success btn-lg product-delete',
                                                                'title' => 'Supprimer '.$product,
                                                            )); 
                        $html .= '</td></tr>';
                    }
                }
                else{
                    $html .= '<tr><th>Pas de produits sur cette commande</th>';
                }
                $html .= '</tbody>';
            }
         } 
         $html .= '</table></div>';
         $response = array(
                'error'      => false,  
                'message'    => '',
                'response'  => $html,
            );
        return $this->response($response);
    }
}