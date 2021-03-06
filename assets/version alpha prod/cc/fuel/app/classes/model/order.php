<?php

class Model_Order extends Model_Crud
{
    protected static $_table_name = 'order';
    protected static $_primary_key = 'order_id';
    protected static $_properties = array(
        'order_id',
        'date',
        'status',
        'customer_id',
    );
    protected static $_created_at = 'date';
    protected static $_mysql_timestamp = true;
    
    const STATUS_DRAFT = 0;
    const STATUS_SUBMITTED = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCEL = 3;
    const STATUS_DELIVERED = 4;
    
    public static $status = array(
        self::STATUS_DRAFT          => 'Enregistrée',
        self::STATUS_SUBMITTED      => 'Validée',
        self::STATUS_PAID           => 'Payée',
        self::STATUS_CANCEL         => 'Annulée',
        self::STATUS_DELIVERED      => 'Livrée',
    );
    
    protected $_customer = false;
    protected $_products = false;
    
    public function get_customer()
    {
        if ($this->_customer === false) {
            $this->_customer = Model_Customer::find_by_pk($this->customer_id);
        }
        return $this->_customer;
    }
    
    public function get_products()
    {
        if ($this->_products === false) {
            $this->_products = Model_Product_Order::find_by('order_id', $this->get_id());
        }
        return $this->_products;   
    }
    
    public function is_finished()
    {
        if ($this->get_products()) {
            foreach ($this->get_products() as $product) {
                if (!$product->is_cooked()) {
                    return false;
                }
            }
        }
        
        return true;
    }
}
