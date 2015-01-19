<?php

/**
 * Class Autoloader - Klasa obsługująca ładowanie klas.
 */
class Autoloader {

    // Tablica ze ścieżkami do głównych katalogów z klasami aplikacji.
    protected static $_paths = [CORE_PATH, CONTROLLERS_PATH, MODELS_PATH];

    public static function register() {
        return spl_autoload_register(['Autoloader', 'load']);
    }

    public static function load($className) {

        // Iterator ścieżek.
        $pathIterator = new ArrayIterator(self::$_paths);
        // Flaga informująca, czy została znaleziona klasa.
        $pathCorrect = false;
        // Pełna ścieżka do pliku z klasą.
        $pathToClass = null;

        // Iteruję po ścieżkach, szukając żądanej klasy.
        while (false === $pathCorrect && $pathIterator->valid()) {

            $path = $pathIterator->current() . $className . '.php';

            if (file_exists($path)) {
                $pathCorrect = true;
                $pathToClass = $path;
            }

            $pathIterator->next();
        }

        // Jeśli udało się znaleźć klasę i można odczytać ten plik, załodowuję ją.
        if (true === $pathCorrect && is_string($pathToClass) && is_readable($pathToClass)) {
            require_once $pathToClass;
        }
    }

}
