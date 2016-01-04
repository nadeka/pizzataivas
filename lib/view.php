<?php

class View
{
    public static function make($view, $content = array())
    {
        $twig = self::get_twig();

        try {
            self::set_flash_message($content);

            $content['current_uri'] = $_SERVER['REQUEST_URI'];

            if (method_exists('BaseController', 'get_user_logged_in')) {
                $content['user_logged_in'] =
                          BaseController::get_user_logged_in();
            }

            if (method_exists('BaseController', 'get_cart')) {
                $content['cart'] = BaseController::get_cart();

                if ($content['cart'] != null) {
                    foreach ($content['cart']['items'] as $index => $item_json) {
                        $content['cart']['items'][$index] = json_decode($item_json);
                    }
                }
            }

            echo $twig->render($view, $content);
        } catch (Exception $e) {
            die('Virhe näkymän näyttämisessä: '.$e->getMessage());
        }

        exit();
    }

    private static function get_twig()
    {
        Twig_Autoloader::register();

        $twig_loader = new Twig_Loader_Filesystem('app/views');

        return new Twig_Environment($twig_loader);
    }

    private static function set_flash_message(&$content)
    {
        if (isset($_SESSION['flash_message'])) {
            $flash = json_decode($_SESSION['flash_message']);

            foreach ($flash as $key => $value) {
                $content[$key] = $value;
            }

            unset($_SESSION['flash_message']);
        }
    }
}
