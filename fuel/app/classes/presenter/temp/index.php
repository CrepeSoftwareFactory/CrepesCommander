<?php

/**
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Temp_Index extends Presenter
{
	public function view()
	{
            $this->count_delivered = Model_Order::count('order_id', true, function($query) {
                $query->where('status', Model_Order::STATUS_DELIVERED);
            });
        
            $this->count_salt = Model_Product_Order::count('product_order_id', true, function($query) {
                $query
                    ->join(array('product', 'pr'), 'INNER')
                        ->on('pr.product_id', '=', 'product_order.product_id')
                    ->join(array('order', 'or'), 'INNER')
                        ->on('or.order_id', '=', 'product_order.order_id')
                    ->where('or.status', Model_Order::STATUS_DELIVERED)
                    ->where('pr.type', Model_Product::TYPE_SALT)
                ;
            });
            
            $this->count_sweet = Model_Product_Order::count('product_order_id', true, function($query) {
                $query
                    ->join(array('product', 'pr'), 'INNER')
                        ->on('pr.product_id', '=', 'product_order.product_id')
                    ->join(array('order', 'or'), 'INNER')
                        ->on('or.order_id', '=', 'product_order.order_id')
                    ->where('or.status', Model_Order::STATUS_DELIVERED)
                    ->where('pr.type', Model_Product::TYPE_SWEET)
                ;
            });
            
            $this->sales = Model_Product_Order::find();
            $this->price = 0;
            foreach($this->sales as $i)
            {
                $this->price += intval($i->get_price());
            }
            
            $this->types = Model_Product_Type::find();
            
	}
}
