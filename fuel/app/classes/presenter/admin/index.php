<?php

class Presenter_Admin_Index extends Presenter 
{
    public function view()
    {
        $this->stations = Model_Station::find();
        $this->products = Model_Product::find(function($query) {
            $query
                ->order_by('type', 'ASC')
            ;
        });
        $this->types = Model_Product_Type::find();
    }
}
