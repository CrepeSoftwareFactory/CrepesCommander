<?php

class Controller_Product_Order extends Controller_Template 
{
    public function action_index()
    {
        $this->template->menu = 'product-order';
        $this->template->css = array('product/order/index.css');
        $this->template->js = array('product/order/index.js');
        
        $presenter = Presenter::forge('product/order/index');
        $this->template->content = $presenter;
    }
    
//    public function action_cook($station_id)
//    {
//        try {
//           DB::start_transaction();
//           $station = Model_Station::find_by_pk($station_id);
//           
//           $cooking_product = $station->get_cooking_product();
//           if ($cooking_product) {
//               $cooking_product->end = date('Y-m-d H:i:s');
//               if (!$cooking_product->save()) {
//                   throw new Exception($cooking_product->validation()->show_errors());
//               }
//           }
//           
//           $waiting_products = $station->get_waiting_products();
//           if ($waiting_products) {
//               $waiting_products[0]->start = date('Y-m-d H:i:s');
//               if (!$waiting_products[0]->save()) {
//                   throw new Exception($waiting_products[0]->validation()->show_errors());
//               }
//           } else {
//               $unaffected_products = Model_Product_Order::get_unaffected();
//               Log::error(print_r($unaffected_products, true));
//               if ($unaffected_products) {
//                    $unaffected_products[0]->start = date('Y-m-d H:i:s');
//                    $unaffected_products[0]->station_id = $station_id;
//                    if (!$unaffected_products[0]->save()) {
//                        throw new Exception($unaffected_products[0]->validation()->show_errors());
//                    }
//               }
//           }
//           
//           DB::commit_transaction();
//        } catch (Exception $ex) {
//            DB::rollback_transaction();
//            Session::set_flash('errors', $ex->getMessage());
//        }
//        
//        Response::redirect('product/order');
//    }
    
    public function action_affect($product_id = null, $station_id = null)
    {
        $this->template->menu = 'product-order-affect';
        $this->template->css = array('product/order/affect.css');
        $this->template->js = array('product/order/affect.js');
        
        if ($product_id) {
            try {
                $product = Model_Product_Order::find_by_pk($product_id);
                $product->station_id = $station_id;
                if (!$product->save()) {
                    throw new Exception($product->validation()->show_errors());
                }
            } catch (Exception $ex) {
                Session::set_flash('errors', $ex->getMessage());
            }
            
            Response::redirect('product/order/affect');
        }
        
        $presenter = Presenter::forge('product/order/affect');
        $this->template->content = $presenter;
    }
    
    public function action_delete($product_id) 
    {
        try {
            $product = Model_Product_Order::find_by_pk($product_id);
            if (!$product->delete()) {
                throw new Exception('Erreur lors de la suppression');
            }
        } catch (Exception $ex) {
            Session::set_flash('errors', $ex->getMessage());
        }
        
        Response::redirect('product/order/affect');
    }
}

