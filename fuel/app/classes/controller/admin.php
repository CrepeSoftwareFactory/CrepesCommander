<?php

class Controller_Admin extends Controller_Template
{
    public function action_index()
    {
        $this->template->menu = 'admin';
        $this->template->js = array('admin/index.js');
        $this->template->css = array('admin.css');
        $presenter = Presenter::forge('admin/index');
        $this->template->content = $presenter;
    }
}