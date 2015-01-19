<?php

class DBModel {

    protected static $_instance;
    protected static $_user = 'root';
    protected static $_password = 'mysql';
    protected static $_host = 'localhost';
    protected static $_port = '3306';
    protected static $_dbname = 'SimpleBlog';
    protected static $_connectionOptions = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

    public static function getInstance() {
        if (!self::$_instance) {

            $connectionString = 'mysql:host=' . self::$_host . ';port=' . self::$_port . ';dbname=' . self::$_dbname;
            $connection = new PDO($connectionString, self::$_user, self::$_password, self::$_connectionOptions);

            self::$_instance = $connection;
        }

        return self::$_instance;
    }

}