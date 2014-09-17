<?php

class Presenter_Order_Add extends Presenter 
{
    public function view()
    {
        Asset::js('order/add.js');
        $this->types = Model_Product::$types;
        $this->products = array();
        foreach ($this->types as $key => $type) {
            $this->products[$key] = Model_Product::find_by('type', $key);
        }
    }
}
