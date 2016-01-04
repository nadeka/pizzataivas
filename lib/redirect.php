<?php

class Redirect
{
    public static function to($path, $message = null)
    {
        if (!is_null($message)) {
            $_SESSION['flash_message'] = json_encode($message);
        }

        header('Location: '.$path);

        exit();
    }
}
