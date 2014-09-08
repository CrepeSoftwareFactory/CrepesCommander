<?php

class Controller_Home extends Controller_Template
{
    public function action_index()
    {
       $presenter = Presenter::forge('home/index');
       $this->template->content = $presenter;
    }
}