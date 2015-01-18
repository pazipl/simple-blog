<?php

class UserController extends Controller{

    public function __construct() {
        parent::__construct();
    }

    public function actionLogout() {
        UserModel::logout();

        header("Location: " . BASE_APP_FOLDER);
    }

    public function actionLogin() {
        $inputLogin = filter_input(INPUT_POST, 'inputLogin', FILTER_DEFAULT);
        $inputPassword = filter_input(INPUT_POST, 'inputPassword', FILTER_DEFAULT);

        $model = new UserModel();

        if (!empty($_POST)) {
            $model->setLogin($inputLogin);
            $model->setPassword($inputPassword);
            $model->auth();

            if ($model->valid()) {
                header("Location: " . BASE_APP_FOLDER);
                return;
            }
        }

        $view = $this->_view;
        $viewParams = [
            'headerText' => 'Zaloguj się',
            'model' => $model
        ];

        $view->render('user/login', $viewParams);
    }

}