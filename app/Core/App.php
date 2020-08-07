<?php

class App
{

    protected $params = [];

    protected $_controller = null;

    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function __construct()
    {
        $this->urlProcess();
    }


    public function urlProcess()
    {
        $arr = $this->getUrlPath();
        $currentController = isset($arr[0]) ? $arr[0] : 'login';
        //xu li controller
        if (!file_exists("./app/Controllers/" . ucfirst($currentController) . "Controller.php")) {
            echo 'Not found controller !';die;
        }
        require_once "./app/Controllers/" . $currentController . "Controller.php";
        $controller = $currentController . 'Controller';
        $controllerObj = new $controller();
        $controllerObj->setCurrentController($currentController);
        $this->setController($controllerObj);
    }

    public function getUrlPath()
    {
        if (isset($_GET['url'])) {
            return explode("/", filter_var(trim($_GET['url'], "/")));
        }
        return [];
    }

    public function exec()
    {
        $arr = $this->getUrlPath();
        //Xu li action
        $action = isset($arr[1]) ? $arr[1] : 'index';
        if ($action && method_exists($this->getController(), $action)) {
            $this->getController()->setCurrentAction($action);
        }

        //xu li params
        $this->params = $arr ? array_values($arr) : [];

        call_user_func_array([$this->getController(), $action], $this->params);// chay controller
    }
}