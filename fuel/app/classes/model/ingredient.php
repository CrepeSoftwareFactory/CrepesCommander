<?php

class Model_Ingredient extends Model_Crud
{
    protected static $_table_name = 'ingredient';
    protected static $_primary_key = 'ingerdient_id';
    protected static $_properties = array(
        'ingredient_id',
        'code',
        'name', 
    );
    
    public $ingredient_id;
    public $code;
    public $name;
}