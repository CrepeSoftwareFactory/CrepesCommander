<?php

/**
 *
 * @author Aurelien
 */
class Model_Crud extends Fuel\Core\Model_Crud 
{
    public function get_id()
    {
        return $this->{static::primary_key()};
    }
}
