<?php

class Controller_Rest_Product_Order extends Controller_Rest 
{
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
    
    public function post_refresh(){
        try
        {
            DB::start_transaction();
            $liste_attente = '<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>';
            $station_id = Input::post('id');
            $return_alone_product = '';
            $station = Model_Station::find_by_pk($station_id);
            $cooking_product = $station->get_cooking_product();
            $waiting_products = $station->get_waiting_products();
            $unaffected_products = Model_Product_Order::get_unaffected();
            
            if($cooking_product && $cooking_product['comment']!==null) { 
                $icone_commentaire = '<a href="#"><span class="glyphicon glyphicon-comment"></span></a>';
            }else{
                $icone_commentaire = '';
            }
            $produit = Html::anchor('product/order/cook/'.$station->get_id(), $cooking_product ?: 'Vide !', array('class' => 'cook')) . $icone_commentaire;

            if(count($waiting_products) > 0){
                $liste_attente = '';
                foreach ($waiting_products as $i => $product) {
                       $liste_attente .= '<li class="panel-body proco_pile_waiting">'.$product.'</span></li>';
                }
            }
            foreach (Model_Product::$types as $key => $type) { 
                $return_alone_product .= '<div class="col-md-4"><h3>'.$type.'</h3><ul class="colonne_pile">';
                if ($unaffected_products) { 
                    foreach ($unaffected_products as $product) { 
                         if ($product->get_product()->type == $key) {
                             $return_alone_product .= '<li>'.$product.'</li>';
                         } 
                    }   
                }
                $return_alone_product .= '</ul></div>';
            }
            
            
            DB::commit_transaction();
           
            $response = array(
                'error'         => false,  
                'message'       => $produit,
                'attente'       => $liste_attente,
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
           
            $waiting_products = $station->get_waiting_products();
            $unaffected_products = Model_Product_Order::get_unaffected();
            if ($waiting_products) {
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
                           $liste_attente .= '<li class="panel-body proco_pile_waiting">'.$product.'</span></li>';
                       }
                    }
               }
               
                    
            } else {
                $liste_attente = '<li class="panel-body proco_pile_waiting">Aucune commande en attente.</li>';
                
                Log::error(print_r($unaffected_products, true));
                if ($unaffected_products) {
                    $unaffected_products[0]->start = date('Y-m-d H:i:s');
                    $unaffected_products[0]->station_id = $station_id;
                    $future_product = $unaffected_products[0];
                    if (!$unaffected_products[0]->save()) {
                        throw new Exception($unaffected_products[0]->validation()->show_errors());
                    }
                    unset($unaffected_products[0]);
                }
            }
            
            foreach (Model_Product::$types as $key => $type) { 
                $return_alone_product .= '<div class="col-md-4"><h3>'.$type.'</h3><ul class="colonne_pile">';
                if ($unaffected_products) { 
                    foreach ($unaffected_products as $product) { 
                         if ($product->get_product()->type == $key) {
                             $return_alone_product .= '<li>'.$product.'</li>';
                         } 
                    }   
                }
                $return_alone_product .= '</ul></div>';
            }

            if($future_product && $future_product['comment']!==null) { 
                $icone_commentaire = '<a href="#"><span class="glyphicon glyphicon-comment"></span></a>';
            }else{
                $icone_commentaire = '';
            }
            $produit = Html::anchor('product/order/cook/'.$station->get_id(), $future_product ?: 'Vide !', array('class' => 'cook')) . $icone_commentaire;
            
           DB::commit_transaction();
           
            $response = array(
                'error'         => false,  
                'message'       => $produit,
                'attente'       => $liste_attente,
                'alone_product'       => $return_alone_product,
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
}