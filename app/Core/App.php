<?php

class App
{

    protected $params = [];

    public function __construct()
    {
        $arr = $this->UrlProcess();
        $currentController = isset($arr[0]) ? ucfirst($arr[0]) : '';
        if (empty($currentController)) {
            echo 'Error !';
            return;
        }

        //xu li controller
        if (!file_exists("./app/Controllers/" . $currentController . "Controller.php")) {
            echo 'Error !';
            return;
        }
        require_once "./app/Controllers/" . $currentController . "Controller.php";
        $controller = $currentController . 'Controller';
        $controllerObj = new $controller();
        $controllerObj->setCurrentController($currentController);

        //Xu li action
        $action = isset($arr[1]) ? $arr[1] : 'index';
        if ($action && method_exists($controllerObj, $action)) {
            $controllerObj->setCurrentAction($action);
        }

        //xu li params
        $this->params = $arr ? array_values($arr) : [];

        call_user_func_array([$controllerObj, $action], $this->params);// chay controller
    }


    public function UrlProcess()
    {
        if (isset($_GET['url'])) {
            return explode("/", filter_var(trim($_GET['url'], "/")));
        }
    }


}

?>