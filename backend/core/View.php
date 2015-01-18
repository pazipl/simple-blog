<?php

class View {

    const SCRIPT_POS_HEAD = 0;
    const SCRIPT_POS_BOTTOM = 1;

    protected $_useLayout = true;
    protected $_content;

    protected $_viewName = null;
    protected $_viewParams = array();

    protected $_headScripts = [];
    protected $_bottomScripts = [];

    public function __construct () {}

    public function useLayout ($flag = true) {
        $this->_useLayout = (bool) $flag;
    }

    public function getLayoutPath () {
        return VIEWS_PATH . 'layout' . DS . 'main.php';
    }

    public function render ($viewName, $viewParams = array()) {
        $this->_viewName = VIEWS_PATH . $viewName . '.php';
        $this->_viewParams = $viewParams;

        $this->_useLayout ? $this->renderLayout() : $this->renderContent();
    }

    public function renderLayout() {
        $this->_loadView($this->getLayoutPath());
    }

    public function renderContent() {
        $this->_loadView($this->_viewName);
    }

    protected function _loadView ($filePath) {
        // Wypakowuje parametry, dostępne dla widoku.
        extract($this->_viewParams);
        // Wyświetlam widok.
        require_once $filePath;
    }


    public function addScript($scriptLocation, $scriptPosition = self::SCRIPT_POS_HEAD) {

        if (!is_string($scriptLocation)) {
            throw new InvalidArgumentException('Nieprawdłowy argument $scriptLocation.');
        }

        $scripts = null;

        switch ($scriptPosition) {
            case self::SCRIPT_POS_HEAD:
                $scripts = & $this->_headScripts;
                break;

            case self::SCRIPT_POS_BOTTOM:
                $scripts = & $this->_bottomScripts;
                break;
        }

        $scripts[] = '<script type="text/javascript" src="'.$scriptLocation.'"></script>';



    }


    public function renderHeadScripts() {
        $this->renderScriptArray($this->_headScripts);
    }

    public function renderBottomScripts() {
        $this->renderScriptArray($this->_bottomScripts);
    }

    protected function renderScriptArray($scripts = []) {

        if (!is_array($scripts)) {
            throw new InvalidArgumentException('Nieprawdłowy argument $scripts.');
        }

        foreach($scripts as $key => $script) {
            echo $script;
        }

    }

}