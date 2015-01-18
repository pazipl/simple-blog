<?php

class UserModel {

    private $_login;
    private $_password;

    public $errorMessage = null;

    public function __construct() {}

    public function setLogin($inputLogin) {
        $this->_login = $inputLogin;
    }

    public function getLogin() {
        return $this->_login;
    }

    public function setPassword($inputPassword) {
        $this->_password = $inputPassword;
    }

    public function getPassword() {
        return $this->_password;
    }

    public function valid() {
        return ($this->_login === 'User' && $this->_password === 'SuperTajneHaslo2015');
    }

    public function auth() {
        if ($this->valid()) {
            self::openSession();
            self::login();
        } else {
            $this->errorMessage = 'Nieprawidłowe dane logowania.';
        }
    }

    public static function openSession() {
        if (empty(session_id())) {
            session_start();
        }
    }

    public static function isLogged() {
        return !empty($_SESSION['auth']);
    }

    public static function login() {
        $_SESSION['auth'] = 1;
    }

    public static function logout() {
        unset($_SESSION['auth']);
    }

}