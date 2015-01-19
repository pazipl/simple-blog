<?php

/**
 * Class Controller Główny kontroler aplikacji.
 */
class Controller {

    // Właściwość przechowuje instancję widoku dla kontrolerów.
    protected $_view;

    public function __construct() {
        $this->_view = new View();
    }

}