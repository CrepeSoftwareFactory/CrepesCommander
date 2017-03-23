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
    
    public function post_cook()
    {
        try {
           DB::start_transaction();
            $station_id = Input::post('id');
           $station = Model_Station::find_by_pk($station_id);
           
           $cooking_product = $station->get_cooking_product();
           if ($cooking_product) {
               $cooking_product->end = date('Y-m-d H:i:s');
               if (!$cooking_product->save()) {
                   throw new Exception($cooking_product->validation()->show_errors());
               }
           }
           
           $waiting_products = $station->get_waiting_products();
           if ($waiting_products) {
               $waiting_products[0]->start = date('Y-m-d H:i:s');
               if (!$waiting_products[0]->save()) {
                   throw new Exception($waiting_products[0]->validation()->show_errors());
               }
           } else {
               $unaffected_products = Model_Product_Order::get_unaffected();
               Log::error(print_r($unaffected_products, true));
               if ($unaffected_products) {
                    $unaffected_products[0]->start = date('Y-m-d H:i:s');
                    $unaffected_products[0]->station_id = $station_id;
                    if (!$unaffected_products[0]->save()) {
                        throw new Exception($unaffected_products[0]->validation()->show_errors());
                    }
               }
           }
           
           DB::commit_transaction();
           
            $response = array(
              'error'       => false,  
              'message'     => 'Message',  
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