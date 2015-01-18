<?php

class ErrorController extends Controller {

    public function __construct(Exception $error) {
        parent::__construct();

        $view = & $this->_view;
        $viewParams = [
            'headerText' => 'Błąd!',
            'errorMessage' => $error->getMessage()
        ];

        $view->render('error/index', $viewParams);

    }

}