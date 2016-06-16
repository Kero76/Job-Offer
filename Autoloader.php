<?php

/**
 *
 * @author Nicolas GILLE
 */

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

class Autoloader {

    /**
     * 
     */
    public static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
    /**
     * 
     * @param type $class
     */
    public static function autoload($class){
        $array = explode('\\', ROOT . $class);
        $path = implode(DIRECTORY_SEPARATOR, $array);   
        $path .= '.php';
        if (file_exists($path)) {
            require_once($path);
        } else {
            throw new Exception('Unable to load ' . $path);
        }
    }
}