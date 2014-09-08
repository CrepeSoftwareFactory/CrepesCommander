<?php

/**
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Product_Index extends Presenter
{
	public function view()
	{
            $this->products = Model_Product::find();
	}
}
