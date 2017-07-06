<?php

class Model_Note extends Model_Crud
{
    protected static $_table_name = 'note';
    protected static $_primary_key = 'note_id';
    protected static $_properties = array(
        'note_id',
        'content',
        'date_crea',
        'categorie_id'
    );
}