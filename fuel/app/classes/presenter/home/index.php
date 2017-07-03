<?php

/**
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Home_Index extends Presenter
{
	public function view()
	{
        $this->stations = Model_Station::find();
	}
}
