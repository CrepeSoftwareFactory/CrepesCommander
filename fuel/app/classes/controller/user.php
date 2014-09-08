<?php

class Controller_User extends Fuel\Core\Controller_Template
{
    public function action_login()
    {
        try {
            if (Input::method() == 'POST') {
                if (!Model_User::login(Input::post('login'), Input::post('password'))) {
                    throw new Exception('Identifiants invalides');
                }
            }
        } catch (Exception $ex) {
            Session::set_flash('errors', $ex->getMessage());
        }
        
        if (Model_User::is_auth()) {
            Response::redirect('home');
        }
        
       $presenter = Presenter::forge('user/login');
       $this->template->content = $presenter;
    }
    
    public function action_logout()
    {
        if ($user = Session::get('user')) {
            $user->logout();
        }
        Response::redirect('user/login');
    }
}