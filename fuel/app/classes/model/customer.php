<?php

class Model_Customer extends Model_Crud
{
    protected static $_table_name = 'customer';
    protected static $_primary_key = 'customer_id';
    protected static $_properties = array(
        'customer_id',
        'lastname',
        'firstname',
        'email',
        'zip',
    );
    protected static $_rules = array(
        'lastname'      => 'required',
    );
    public $customer_id;
    public $lastname;
    public $firstname;
    public $email;
    public $zip;
    
    protected $_orders = false;
    
    public function get_orders()
    {
        if ($this->_orders === false) {
            $this->_orders = Model_Order::find_by('customer_id', $this->get_id());
        }
        return $this->_orders;
    }
}

