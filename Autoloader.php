<?php
namespace App;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    static function autoload($class){
        $class = str_replace(__NAMESPACE__.'\\', '', $class);
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $class = __DIR__.DIRECTORY_SEPARATOR.$class.'.php';
        if(file_exists($class))
        {
            require_once $class;
        }
    }
}