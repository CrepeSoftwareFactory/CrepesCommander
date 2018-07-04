<?php

class Presenter_Product_Order_List extends Presenter 
{
    public function view()
    {
        $this->maPile = Session::get('maPile');
        $this->stations = Model_Station::find();
        $this->orders = Model_Order::find(function($query) {
            $query
                ->where('status', 'NOT IN', array(Model_Order::STATUS_CANCEL, Model_Order::STATUS_DELIVERED))
                ->order_by('date', 'DESC')
            ;
        });
        $this->statuses = Model_Proco_Status::find();
    }
}