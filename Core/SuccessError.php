<?php
namespace App\Core;

abstract class SuccessError
{
    public static function redirect(array $type, string $redirect) : void
    {
        foreach($type as $key => $message)
        {
            $_SESSION[$key] = $message;
        }
        header($redirect);
        exit;
    }
}