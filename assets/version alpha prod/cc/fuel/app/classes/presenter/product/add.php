<?php

/**
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Product_Add extends Presenter
{
	public function view()
	{
            $this->types = Model_Product::$types;
	}
}
