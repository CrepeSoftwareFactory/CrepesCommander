<?php

class Controller_Template extends Fuel\Core\Controller_Template
{
    public function before()
    {
        parent::before();
//        if (!Model_User::is_auth()) {
//            Session::set_flash('errors', 'Connexion nécessaire');
//            Response::redirect('user/login');
//        }
    }
}