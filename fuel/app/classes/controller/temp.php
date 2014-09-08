<?php

class Controller_Temp extends Controller_Template 
{
    public function action_index()
    {
        $this->template->menu = 'temp';
        
        $presenter = Presenter::forge('temp/index');
        $this->template->content = $presenter;
    }
}

