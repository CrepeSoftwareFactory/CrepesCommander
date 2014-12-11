<?php

class Model_Product_Order extends Model_Crud
{
    protected static $_table_name = 'product_order';
    protected static $_primary_key = 'product_order_id';
    protected static $_properties = array(
        'product_order_id',
        'product_id',
        'order_id',
        'start', 
        'end',
        'priority',
        'price',
        'station_id',
        'free',
        'comment',
    );
    
    protected $_station = false;
    protected $_product = false;
    protected $_order = false;
    
    public function get_station()
    {
        if ($this->_station === false) {
            $this->_station = Model_Station::find_by_pk($this->station_id);
        }
        return $this->_station;
    }
    
    public function get_product()
    {
        if ($this->_product === false) {
            $this->_product = Model_Product::find_by_pk($this->product_id);
        }
        return $this->_product;
    }
    
    public function get_order()
    {
        if ($this->_order === false) {
            $this->_order = Model_Order::find_by_pk($this->order_id);
        }
        return $this->_order;
    }
    
    public static function get_unaffected()
    {
        return self::find_by(function($query)  {
            $query
                ->join(array('order', 'or'), 'INNER')
                    ->on('or.order_id', '=', 'product_order.order_id')
                ->where('station_id', null)
                ->where('start',  null)
                ->where('end', null)
                ->where('or.status', 'IN', array(Model_Order::STATUS_SUBMITTED, Model_Order::STATUS_PAID))
                ->order_by('or.date', 'ASC')    
            ;
        });
    }
    
    public function get_price()
    {
        return $this->price;
    }
    
    public function is_waiting()
    {
        return (!$this->start && !$this->end);
    }
    
    public function is_cooking()
    {
        return ($this->start && !$this->end);
    }
    
    public function is_cooked()
    {
        return ($this->start && $this->end);
    }
    
    public function __toString()
    {
        return $this->get_product()->name.' - <span>'.$this->get_order()->get_customer()->lastname.'</span>';
    }
}