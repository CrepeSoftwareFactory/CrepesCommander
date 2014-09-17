<?php

class Model_Station extends Model_Crud 
{
    protected static $_table_name = 'station';
    protected static $_primary_key = 'station_id';
    protected static $_properties = array(
        'station_id',
        'name',
        'state',
    );
    
    const STATE_OK = 0;
    const STATE_KO = 1;
    
    protected $_products = false;
    protected $_cooking_product = false;
    protected $_waiting_products = false;
    
    public function get_products()
    {
        if ($this->_products === false) {
            $this->_products = Model_Product_Order::find_by('station_id', $this->get_id());
        }
        return $this->_products;
    }
    
    public function get_cooking_product()
    {
        if ($this->_cooking_product === false) {
            $station_id = $this->get_id();
            $this->_cooking_product = Model_Product_Order::find_one_by(function($query) use($station_id) {
                $query
                    ->where('station_id', $station_id)
                    ->where('start', '!=', null)
                    ->where('end', null)
                ;
            });
        }
        return $this->_cooking_product;
    }
    
    public function get_waiting_products()
    {
        if ($this->_waiting_products === false) {
            $station_id = $this->get_id();
            $this->_waiting_products = Model_Product_Order::find_by(function($query) use($station_id) {
                $query
                    ->join(array('order', 'or'), 'INNER')
                        ->on('or.order_id', '=', 'product_order.order_id')
                    ->where('station_id', $station_id)
                    ->where('start',  null)
                    ->where('end', null)
                    ->where('or.status', 'IN', array(Model_Order::STATUS_SUBMITTED, Model_Order::STATUS_PAID))
                    ->order_by('or.date', 'ASC')    
                ;
            });
        }
        return $this->_waiting_products;
    }
    
    /**
     * @return boolean
     */
    public function is_usable()
    {
        switch ($this->state) {
            case self::STATE_OK:
                return true;
            case self::STATE_KO:
                return false;
        }
    }
}
