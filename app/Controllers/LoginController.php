<?php


class LoginController extends BaseController
{


    public function login()
    {
        $this->renderView("index");
        if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
            return header("location: ../user/index");
        }
        if (isset($_POST['login'])) {
            $user = $this->getModel()->getData([
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            ]);
            if ($user) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['password'] = $user['password'];
                return header("location: ../user/index");
            }
        }
       if ($this->isLoggedIn()) {
            header("location: ../user/index");
        }
    }

    public function logout()
    {
        session_destroy();
        header("location: ../login/login");
    }


}

?>