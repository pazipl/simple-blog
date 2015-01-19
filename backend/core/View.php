<?php

/**
 * Class View
 */
class View {

    // Pozycja skryptu w sekcji <head>.
    const SCRIPT_POS_HEAD = 0;

    // Pozycja skryptu przed zamknięciem <body>.
    const SCRIPT_POS_BOTTOM = 1;

    // Flaga informuje, czy używać przy renderowaniu widoku szablonu /views/layout/main.php
    protected $_useLayout = true;

    // Właściwość przechowuje renderowany kontent.
    protected $_content;

    // Nazwa widoku w katalogu views.
    protected $_viewName = null;

    // Parametry widoku.
    protected $_viewParams = array();

    // Tablica skyptów w sekcji <head>.
    protected $_headScripts = [];

    // Tablica skryptów w zakończeniu <body>
    protected $_bottomScripts = [];

    public function __construct() {
    }

    public function useLayout($flag = true) {
        $this->_useLayout = (bool)$flag;
    }

    /**
     * @return string Absolutna ścieżka do pliku z głównym szablonem.
     */
    public function getLayoutPath() {
        return VIEWS_PATH . 'layout' . DS . 'main.php';
    }

    /**
     * Renderuje widok w szablonie lub bez.
     *
     * @param $viewName
     * @param array $viewParams
     */
    public function render($viewName, $viewParams = array()) {
        $this->_viewName = VIEWS_PATH . $viewName . '.php';
        $this->_viewParams = $viewParams;

        $this->_useLayout ? $this->renderLayout() : $this->renderContent();
    }

    /**
     * Renderuje szablon.
     */
    public function renderLayout() {
        $this->_loadView($this->getLayoutPath());
    }

    /**
     * Renderuje kontent strony.
     */
    public function renderContent() {
        $this->_loadView($this->_viewName);
    }

    /**
     * Załącza i wyświetla plik z widokiem.
     *
     * @param $filePath
     */
    protected function _loadView($filePath) {
        // Wypakowuje parametry, dostępne dla widoku.
        extract($this->_viewParams);

        // Wyświetlam widok.
        require_once $filePath;
    }

    /**
     * Metoda dodaje do tablicy skryptów, skrypty wyświetlane w odpowiedniej pozycji dokumentu.
     *
     * @param $scriptLocation
     * @param int $scriptPosition
     */
    public function addScript($scriptLocation, $scriptPosition = self::SCRIPT_POS_HEAD) {

        if (!is_string($scriptLocation)) {
            throw new InvalidArgumentException('Nieprawdłowy argument $scriptLocation.');
        }

        $scripts = null;

        switch ($scriptPosition) {
            case self::SCRIPT_POS_HEAD:
                $scripts = &$this->_headScripts;
                break;

            case self::SCRIPT_POS_BOTTOM:
                $scripts = &$this->_bottomScripts;
                break;
        }

        $scripts[] = '<script type="text/javascript" src="' . $scriptLocation . '"></script>';

    }

    /**
     * Metoda renderuje skrypty w sekcji <head>.
     */
    public function renderHeadScripts() {
        $this->renderScriptArray($this->_headScripts);
    }

    /**
     * Metoda renderuje skrypty w zakończeniu <body>.
     */
    public function renderBottomScripts() {
        $this->renderScriptArray($this->_bottomScripts);
    }

    /**
     * Metoda wyświetla przekazane skrypty.
     *
     * @param array $scripts
     */
    protected function renderScriptArray($scripts = []) {

        if (!is_array($scripts)) {
            throw new InvalidArgumentException('Nieprawdłowy argument $scripts.');
        }

        foreach ($scripts as $key => $script) {
            echo $script;
        }

    }

}