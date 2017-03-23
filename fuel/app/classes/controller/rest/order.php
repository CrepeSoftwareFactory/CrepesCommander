<?php

class Controller_Rest_Order extends Controller_Rest 
{
    
    //Fonction pour ajouter dans la bdd les différentes proco
    public function post_add()
    {
        try {
            DB::start_transaction();
            $order = new Model_Order();
            $customer = new Model_Customer();
            
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

            foreach (Input::post('products') as $product_id => $product_data) {
                $product = Model_Product::find_by_pk($product_id);
                if ($product === null) {
                    throw new Excepetion('Produit invalide');
                }


                $i = 1;
                while ($i <= $product_data['quantity']) {
                    $product_order = new Model_Product_Order(array(
                        'product_id'    => $product->get_id(),
                        'order_id'      => $order->get_id(),
                        'price'         => $i == $product_data['quantity'] ? $product_data['price'] : ($product->price < $product_data['price'] ? $product->price : $product_data['price']),
                        'free'          => isset($free[$product_id]) ? 1 : 0,
                        'comment'       => $product_data['comment'] ?: null,
                    ));
                    if (!$product_order->save()) {
                        throw new Exception($product_order->validation()->show_errors());
                    }
                    $product_data['price'] -= $product_order->price;
                    $i++;
                }
            }

            DB::commit_transaction();
            
            $response = array(
              'error'       => false,  
              'message'     => 'Commande créée avec succès',  
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