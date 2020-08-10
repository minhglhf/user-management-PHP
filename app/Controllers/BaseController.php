<?php

class BaseController
{
    protected $_controllerName = null;
    protected $_action = null;
    protected $_model = null;
    protected $_keyErrors = null;

    public function __construct()
    {
        $userModel = $this->loadModel("UserModel");
        $this->setModel($userModel);
    }

    public function setKeyErrors($keyError)
    {
        $this->_keyErrors = $keyError;
    }

    public function getKeyErrors()
    {
        return $this->_keyErrors;
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
        if (!isset($_SESSION['login'])) {
            return false;
        }
        return true;
    }


    public function renderView($filename = null, $data = [])
    {
        if (empty($filename)) {
            $filename = $this->getCurrentActionName();
        }

        if (!empty($data))
            extract($data);

        return require_once('./app/views/' . $this->getCurrentControllerName() . '/' . $filename . '.php');
    }

    public function getActionRole()
    {
        $userRole = $this->getModel()->getCurrentUserId($_SESSION['login']['email'])['role'];
        $curAction = $this->getCurrentActionName();
        if ($userRole == "3" && ($curAction == 'showUser' || $curAction == 'update' || $curAction == 'search')) return 1;
        if ($userRole == "2" || $userRole == "1") return 1;
        return 0;
    }

    public function checkRole()
    {
        if ($this->getActionRole() == '0') {
            return $this->getModel()->deniedAccess();
        }
        return 1;
    }

    public function checkRoleDetail($smallerRole)
    {
        $biggerRole = $this->getModel()->getCurrentUserId($_SESSION['login']['email'])['role'];
        if ($biggerRole < $smallerRole) return 1;
        return 0;
    }

    public function redirect($url)
    {
        return header("location:../{$url}");
    }

    public function refresh()
    {
        return header("Refresh:0");
    }

    public function setSession($key, $value)
    {
         $_SESSION[$key] = $value;
    }

    public function deleteSession($key)
    {
        unset($_SESSION[$key]);
    }

    public function deleteError()
    {
        if ($this->getCurrentActionName() != 'create') $this->deleteSession('error');
        if ($this->getCurrentActionName() != 'login') $this->deleteSession('error');
    }

}