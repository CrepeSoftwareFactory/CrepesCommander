<?php

class Model_Product_Ingredient extends Model_Crud
{
    protected static $_table_name = 'product_ingredient';
//    protected static $_primary_key = '';
    protected static $_properties = array(
        'product_id',
        'ingredient_id',
    );
}

