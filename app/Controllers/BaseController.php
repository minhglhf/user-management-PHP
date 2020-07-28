<?php

class BaseController
{
    protected $_controllerName = null;
    protected $_action = null;
    protected $_model = null;

    public function __construct()
    {
        $userModel = $this->loadModel("UserModel");
        $this->setModel($userModel);
    }

    public function setModel($model)
    {
        $this->_model = $model;
    }

    public function getModel()
    {
        return $this->_model;
    }

    public function setCurrentController($controller)
    {
        $this->_controllerName = $controller;
    }

    public function getCurrentControllerName()
    {
        return $this->_controllerName;
    }

    public function setCurrentAction($action)
    {
        $this->_action = $action;
    }

    public function getCurrentActionName()
    {
        return $this->_action;
    }


    public function loadModel($filename)
    {
        require_once('./app/Models/' . $filename . '.php');
        return new $filename;
    }


    public function isLoggedIn()
    {
        if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
            return false;
        }
        return true;
    }

    public function renderView($filename = null, $data = [])
    {
        if (empty($filename)) {
            $filename = $this->getCurrentActionName();
        }
        extract($data);
        require_once('./app/views/' . $this->getCurrentControllerName() . '/' . $filename . '.php');
    }

    public function role(){
        if ($_SESSION['role'] == "3") {
            if($this->getCurrentActionName() == 'showUser' || $this->getCurrentActionName() == 'update' ||
            $this->getCurrentActionName() == 'search') return 1;
            return 0;
        }
        if ($_SESSION['role'] == "2" || $_SESSION['role'] == "1") {
            return 1;
        }
        return 1;
    }

    public function deniedAccess(){
           if($this->role() == '0'){
               echo "Access Denien - Not your role! <br>";
               echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
               die;
           }

    }

}