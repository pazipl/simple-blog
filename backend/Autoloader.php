<?php

class Autoloader {

    protected static $_paths = [CORE_PATH, CONTROLLERS_PATH, MODELS_PATH];

    public static function register () {
        return spl_autoload_register(['Autoloader', 'load']);
    }

    public static function load ($className) {

        $pathIterator = new ArrayIterator(self::$_paths);
        $pathCorrect = false;
        $pathToClass = null;

        while (false === $pathCorrect && $pathIterator->valid()) {

            $path = $pathIterator->current() . $className . '.php';

            if (file_exists($path)) {
                $pathCorrect = true;
                $pathToClass = $path;
            }

            $pathIterator->next();
        }


        if (true === $pathCorrect && is_string($pathToClass) && is_readable($pathToClass)) {
            require_once $pathToClass;
        }
    }

}
