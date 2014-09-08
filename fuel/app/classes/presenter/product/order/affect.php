<?php

class Presenter_Product_Order_Affect extends Presenter 
{
    public function view()
    {
        $this->stations = Model_Station::find();
        $this->orders = Model_Order::find(function($query) {
            $query
                ->where('status', 'NOT IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                ->order_by('date', 'ASC')
            ;
        });
    }
}
