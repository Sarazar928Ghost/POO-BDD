<?php
namespace App\Core;

abstract class Util
{
    /**
     * Permet d'avoir la page actuel de l'utilisateur. Sinon il donne l'index
     *
     * @return string
     */
    public static function getRefererOrRacine() : string
    {
        $location = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        return $location;
    }

    /**
     * Redirige l'utilisateur avec un message de $_SESSION si donné
     *
     * @param string $redirect
     * @param array $type
     * @return void
     */
    public static function redirect(string $redirect, array $type = []) : void
    {
        foreach($type as $key => $message)
        {
            $_SESSION[$key] = $message;
        }
        header('Location: ' . $redirect);
        exit;
    }
}