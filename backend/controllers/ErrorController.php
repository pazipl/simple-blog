<?php

/**
 * Class ErrorController - Kontroler, obsługuje wyjątki rzucone przez aplikację.
 */
class ErrorController extends Controller {

    /**
     * @param Exception $error
     */
    public function __construct(Exception $error) {
        parent::__construct();

        // Tworzę referencję do instancji z widokiem.
        $view = &$this->_view;

        // Parametry widoku.
        $viewParams = [
            'headerText' => 'Błąd!',
            'errorMessage' => $error->getMessage()
        ];

        // Wyświetlam widok, przekazując parametry widoku.
        $view->render('error/index', $viewParams);

    }

}