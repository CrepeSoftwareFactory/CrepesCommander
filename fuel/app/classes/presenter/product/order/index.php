<?php

class Presenter_Product_Order_Index extends Presenter 
{
    public function view()
    {
        $this->stations = Model_Station::find();
        $this->alone_products = Model_Product_Order::find_by(function($query) {
            $query
                ->join(array('order', 'or'), 'INNER')
                    ->on('or.order_id', '=', 'product_order.order_id')
                ->join(array('proco_status', 'pro'), 'INNER')
                    ->on('pro.proco_status_id', '=', 'product_order.status')
                ->where('or.status', 'NOT IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                ->where('product_order.station_id', null)
                ->order_by('pro.priority', 'ASC')
                ->order_by('or.date', 'ASC')    
                ->order_by('product_order_id', 'ASC')    
            ;
        });
    }
}
