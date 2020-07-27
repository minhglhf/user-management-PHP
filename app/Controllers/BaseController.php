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


}