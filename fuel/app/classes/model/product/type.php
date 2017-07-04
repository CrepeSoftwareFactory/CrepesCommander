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
}
