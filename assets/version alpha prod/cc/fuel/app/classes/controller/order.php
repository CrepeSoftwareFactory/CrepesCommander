<?php

class Controller_Order extends Controller_Template 
{
    public function action_index()
    {
        $this->template->content = Presenter::forge('order/index');
    }
    
    public function action_add()
    {
        $this->template->menu = 'order-add';
        $this->template->css = array('order/add.css');
        $this->template->js = array('order/add.js');
        
        try {
            DB::start_transaction();
            $order = new Model_Order();
            $customer = new Model_Customer();
            
            if (Input::method() == 'POST') {
                $free = Input::post('free');
                $customer->set(Input::post());
                if (!$customer->save()) {
                    throw new Exception($customer->validation()->show_errors());
                }
                
                $order->set(array(
                   'customer_id'    => $customer->get_id(),
                    'status'        => Model_Order::STATUS_PAID,
                ));
                if (!$order->save()) {
                    throw new Exception($order->validation()->show_errors());
                }
                
                if (!Input::post('products')) {
                    throw new Exception('Aucun produit défini');
                }
                
                foreach (Input::post('products') as $product_id => $quantity) {
                    $product = Model_Product::find_by_pk($product_id);
                    if ($product === null) {
                        throw new Excepetion('Produit invalide');
                    }
                    
                    $i = 1;
                    while ($i <= $quantity) {
                        $product_order = new Model_Product_Order(array(
                            'product_id'    => $product->get_id(),
                            'order_id'      => $order->get_id(),
                            'price'         => $product->price,
                            'free'          => isset($free[$product_id]) ? 1 : 0,
                        ));
                        if (!$product_order->save()) {
                            throw new Exception($product_order->validation()->show_errors());
                        }
                        $i++;
                    }
                }
                
                DB::commit_transaction();
                Response::redirect('order/add');
            }
        } catch (Exception $ex) {
            DB::rollback_transaction();
            Session::set_flash('errors', $ex->getMessage());
        }
        
        $presenter = Presenter::forge('order/add');
        $presenter->get_view()->set('order', $order);
        $presenter->get_view()->set('customer', $customer);
        $this->template->content = $presenter;
    }
    
    public function action_cancel($order_id)
    {
        try {
            $order = Model_Order::find_by_pk($order_id);
            $order->status = Model_Order::STATUS_CANCEL;
            if (!$order->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            
            Session::set_flash('success', 'Commande annulée');
        } catch (Exception $ex) {
            Session::set_flash('errors', $ex->getMessage());
        }
        
        Response::redirect('product/order/affect');
    }
    
    public function action_finish($order_id)
    {
        try {
            $order = Model_Order::find_by_pk($order_id);
            $order->status = Model_Order::STATUS_DELIVERED;
            if (!$order->save()) {
                throw new Exception($order->validation()->show_errors());
            }
            
            Session::set_flash('success', 'Commande livrée !');
        } catch (Exception $ex) {
            Session::set_flash('errors', $ex->getMessage());
        }
        
        Response::redirect('product/order/affect');
    }
    
    public function action_finished()
    {
        $this->template->menu = 'order-finished';
        $this->template->css = array('product/order/affect.css');
        
        $presenter = Presenter::forge('order/finished');
        $this->template->content = $presenter;
    }
}
