<?php

class Controller_Misc extends Controller_Template 
{
    public function action_index()
    {
        $this->template->menu = 'misc';
        
        $presenter = Presenter::forge('misc/index');
        $this->template->content = $presenter;
    }
}

