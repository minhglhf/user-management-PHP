<?php


class LoginController extends BaseController
{
    public function index()
    {
        if ($this->isLoggedIn()) {
            return $this->redirect('user/index');
        }
        if (isset($_POST['login'])) {
            return $this->_postLogin();
        }
        if(isset($_SESSION['error'])) $this->_keyErrors = $_SESSION['error'];
        return $this->renderView('index', ['list' => $this->getKeyErrors()]);
    }

    protected function _postLogin()
    {
        $params = [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];
        $valid = $this->getModel()->validateLogin($params);
        if (!$valid){
            $this->setSession('error' ,$this->getModel()->getErrors());
            return $this->redirect('login/index');
        }
        $this->setSession('login', $params);
        $this->deleteSession('error');
        return $this->redirect('user/index');
    }

    public function logout()
    {
        session_destroy();
        return $this->redirect('login/index');
    }


}