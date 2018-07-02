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

    public function action_list($product_id=null, $station_id=null)
    {
        $this->template->menu = 'product-order-list';
        $this->template->css = array('product/order/list.css');
        $this->template->js = array('product/order/list.js');
        
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
            
            Response::redirect('product/order/list');
        }
        
        $presenter = Presenter::forge('product/order/list');
        $this->template->content = $presenter;
    }
//Fonction obsolète depuis sa mise en place dans les fonctions REST    
//    public function action_delete($product_id) 
//    {
//        try {
//            $product = Model_Product_Order::find_by_pk($product_id);
//            if (!$product->delete()) {
//                throw new Exception('Erreur lors de la suppression');
//            }
//        } catch (Exception $ex) {
//            Session::set_flash('errors', $ex->getMessage());
//        }
//        
//        Response::redirect('product/order/affect');
//    }
}

