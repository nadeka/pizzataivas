<?php

/**
 * Displays the front page and info page.
 */
class DefaultController extends BaseController
{
    public static function index()
    {
        View::make('index.html');
    }

    public static function info()
    {
        View::make('info.html');
    }
}
