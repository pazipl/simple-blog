<?php

class Bootstrap {

    protected $_rootPath;

    protected $_requestInfo = [
        'controllerName' => 'PostController',
        'actionName' => 'actionIndex',
        'params' => []
    ];


    public function __construct() {
        $this->_buildRequestInfo(filter_input(INPUT_GET, 'requestURL', FILTER_SANITIZE_STRING));
        $this->_runController();

    }


    private function _runController() {

        $controller = $this->_requestInfo['controllerName'];
        $action = $this->_requestInfo['actionName'];
        $params = (array)$this->_requestInfo['params'];

        try {
            $reflection = new ReflectionMethod($controller, $action);
            $reflection->invokeArgs(new $controller, $params);
        } catch (ReflectionException $e) {
            new ErrorController($e);
        }
    }


    private function _buildRequestInfo($requestURL) {

        $requestInfo = &$this->_requestInfo;

        if (!$requestURL) {
            $requestURL = $this->_defaultRoute;
        }

        $parts = explode('/', $requestURL);

        if (!empty($parts[0])) {
            $requestInfo['controllerName'] = ucfirst($parts[0]) . 'Controller';
        }

        if (!empty($parts[1])) {
            $requestInfo['actionName'] = 'action' . ucfirst($parts[1]);
        }

        if (!empty($parts[2])) {
            $requestInfo['params'] = array_slice($parts, 2);
        }

    }

}