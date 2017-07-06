<?php

class Model_Product extends Model_Crud
{
    protected static $_table_name = 'product';
    protected static $_primary_key = 'product_id';
    protected static $_properties = array(
        'product_id',
        'code',
        'name',
        'description',
        'price',
        'time',
        'type',
        'close',
    );
    
    protected static $_rules = array(
        'name'      => 'required',
        'price'     => 'required',
        'type'      => 'required',
    );
    
    protected $_ingredients = false;
    
    const TYPE_SALT     = 0;
    const TYPE_SWEET    = 1;
    const TYPE_OTHER    = 2;
    const ACTIVATED     = 0;
    const DESACTIVATED  = 1;
    
    public static $types = array(
        self::TYPE_SALT     => 'Galette',
        self::TYPE_SWEET    => 'Crepe',
        self::TYPE_OTHER    => 'Autre',
    );
    
    public function get_ingredients()
    {
        if ($this->_ingredients === false) {
            $product_id = $this->get_id();
            $this->_ingredients = Model_Ingredient::find(function($query) use($product_id) {
                $query 
                    ->join(array('product_ingredient', 'pr_in'))
                        ->on('pr_in.id_ingredient', '=', 'ingredient.ingredient_id')
                    ->where('pr_in.product_id', $product_id)
                ;
            });
        }
        
        return $this->_ingredients;
    }
}
