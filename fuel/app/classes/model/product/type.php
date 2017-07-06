<?php

class Model_Product_Type extends Model_Crud
{
    protected static $_table_name = 'product_type';
    protected static $_primary_key = 'type_id';
    protected static $_properties = array(
        'type_id',
        'type_label',
        'type_couleur',
    );
    
    public function get_type_id()
    {
        return $this->type_id;
    }
    
    
    public static function get_productMoy(){
        $products = Model_Product_Order::find();
        if($products){
            foreach($products as $product){
                $val = $product->get_moyTime();
                if($val != 0){
                    $type = $product->get_type();
                    if(isset($tab[$type->type]['inc'])){
                        $tab[$type->type]['inc'] = $tab[$type->type]['inc'] +1;
                        $tab[$type->type]['sum'] = $tab[$type->type]['sum']+ $val;
                    }
                    else{
                        $tab[$type->type]['inc'] = 1;
                        $tab[$type->type]['sum'] = $val;
                    }
                }
            }
            if(isset($tab)){
                return $tab;
            }
            else{
                return 0;
            }
        }
    }
    
    public function get_nbCooked(){
        $nbCooked = Model_Product_Order::count('product_order_id', true, function($query)  {
            $query
                ->join(array('product', 'pr'), 'INNER')
                    ->on('pr.product_id', '=', 'product_order.product_id')
                ->join(array('order', 'or'), 'INNER')
                    ->on('or.order_id', '=', 'product_order.order_id')
                ->where('or.status', Model_Order::STATUS_PAID)
                ->where('pr.type', $this->type_id)
            ;
        });
        return $nbCooked;
    }
    
    public function get_nbDelivered(){
        $nbCooked = Model_Product_Order::count('product_order_id', true, function($query)  {
            $query
                ->join(array('product', 'pr'), 'INNER')
                    ->on('pr.product_id', '=', 'product_order.product_id')
                ->join(array('order', 'or'), 'INNER')
                    ->on('or.order_id', '=', 'product_order.order_id')
                ->where('or.status', Model_Order::STATUS_DELIVERED)
                ->where('pr.type', $this->type_id)
            ;
        });
        return $nbCooked;
    }
    
    public function get_nbWaiting(){
        $nbCooked = Model_Product_Order::count('product_order_id', true, function($query)  {
            $query
                ->join(array('product', 'pr'), 'INNER')
                    ->on('pr.product_id', '=', 'product_order.product_id')
                ->join(array('order', 'or'), 'INNER')
                    ->on('or.order_id', '=', 'product_order.order_id')
                ->where('or.status', Model_Order::STATUS_PAID)
                ->where('product_order.end', NULL)
                ->where('pr.type', $this->type_id)
            ;
        });
        return $nbCooked;
    }
}
