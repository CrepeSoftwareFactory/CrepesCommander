<?php

class Model_Proco_Status extends Model_Crud
{
    protected static $_table_name = 'proco_status';
    protected static $_primary_key = 'proco_status_id';
    protected static $_properties = array(
        'proco_status_id',
        'slug',
        'name',
    );
}
