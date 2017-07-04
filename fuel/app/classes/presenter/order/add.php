<?php

class Presenter_Order_Add extends Presenter 
{
    public function view()
    {
        Asset::js('order/add.js');
        $this->types = Model_Product_Type::find();
        $this->products = array();
        foreach ($this->types as $type) {
            $this->products[$type->type_id] = Model_Product::find_by('type', $type->type_id);
        }
    }
}
