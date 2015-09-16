<?php

error_reporting(E_ALL);

$script_name = $_SERVER['SCRIPT_NAME'];

if ( !session_id() ) {
    session_start();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array('items' => array(), 'price' => 0.0);
    }
}

require 'vendor/autoload.php';

$routes = new \Slim\Slim();
$routes->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

require 'config/routes.php';

$routes->run();
