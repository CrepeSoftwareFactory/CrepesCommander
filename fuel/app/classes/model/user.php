<?php

class Model_User extends Model_Crud 
{
    protected static $_table_name = 'user';
    protected static $_primary_key = 'user_id';
    protected static $_properties = array(
      'user_id',  
      'login',  
      'password',  
    );
    
    public static function login($login, $password)
    {
        try {
            $user = Model_User::find_one_by(function($query) use($login, $password) {
               $query
                    ->where('login', $login)
                    ->where('password', md5($password))
                ;
            });
            
            if (!$user) {
                throw new Exception('Utilisateur invalide');
            }
            
            Session::set('user', $user);
            
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function is_auth()
    {
        return (Session::get('user') !== null);
    }
    
    public function logout()
    {
        if (self::is_auth()) {
            Session::set('user', null);
        }
    }
}