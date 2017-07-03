<?php

class Controller_Admin extends Controller_Template
{
    public function action_index()
    {
        $this->template->menu = 'admin';
        $this->template->js = array('admin/index.js');
        $presenter = Presenter::forge('admin/index');
        $this->template->content = $presenter;
    }
}