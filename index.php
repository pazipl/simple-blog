<?php

// Separator katalogów w systemie.
define('DS', DIRECTORY_SEPARATOR);
// Ścieżka absolutna dla aktualnego pliku.
define('ROOT_PATH', dirname(__FILE__) . DS);

// Ścieżki do katalogów z  Modelami, Widokami, Kontrolerami.
// ---------------------------------------------------------
define('CONTROLLERS_PATH', ROOT_PATH . implode(DS, ['backend','controllers']) .DS);
define('MODELS_PATH', ROOT_PATH . implode(DS, ['backend','models']) .DS);
define('VIEWS_PATH', ROOT_PATH . implode(DS, ['backend','views']) .DS);

// Ścieżka do katalogu z podstawowymi klasami aplikacji.
define('CORE_PATH', ROOT_PATH . implode(DS, ['backend','core']) .DS);

define('UPLOAD_IMAGE_PATH', ROOT_PATH . implode(DS, ['public','upload']) .DS);


// Ścieżki wykorzystywane przy budowaniu linków.
// ---------------------------------------------------------
define('BASE_APP_FOLDER', '/simple-blog');
define('BASE_SCRIPTS', BASE_APP_FOLDER . implode('/', ['/public', 'js']) . '/');
define('BASE_STYLES', BASE_APP_FOLDER . implode('/', ['/public', 'css']) . '/');

// Ładuję Autoloader.
require_once "backend/Autoloader.php";

// Rejestruję ścieżki dla `Autoloader`a.
Autoloader::register();
// Uruchamiam sesję.
UserModel::openSession();

// Tworzę rozruch aplikacji.
$bootstrap = new Bootstrap();
