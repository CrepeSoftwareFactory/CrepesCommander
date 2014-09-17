<?php

class Presenter_Order_Index extends Presenter 
{
    public function view()
    {
        $this->orders = Model_Order::find();
        $this->stations = Model_Station::find();
    }
}
