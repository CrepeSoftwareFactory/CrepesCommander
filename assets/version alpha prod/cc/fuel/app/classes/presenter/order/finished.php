<?php

class Presenter_Order_Finished extends Presenter 
{
    public function view()
    {
        $this->orders = Model_Order::find(function($query) {
            $query
                ->where('status', 'IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                ->order_by('date', 'DESC')
            ;
        });
    }
}
