<?php

class BaseController{

    public static function get_user_logged_in() {
        if ( isset($_SESSION['user']) ) {
            $user_id = $_SESSION['user'];

            return Users::findOne($user_id);
        }

        return null;    
    }

    public static function get_cart() {
        if ( isset($_SESSION['cart']) ) {
            return $_SESSION['cart'];
        }

        return null;    
    }    

    public static function check_logged_in() {
        if ( !isset($_SESSION['user']) ) {
            Redirect::to('/kirjaudu', array(
                'message' => 'Kirjaudu ensin sisään!'));
        }
    }

    public static function check_if_admin() {
        $user = self::get_user_logged_in();

        if ( $user == null || $user->username != 'admin' ) {
            Redirect::to('/kirjaudu', array(
                'message' => 'Sinulla ei ole tarvittavia käyttöoikeuksia!'));
        }
    }   
}
