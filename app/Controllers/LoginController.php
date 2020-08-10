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
        if (isset($_SESSION['error'])) $this->_keyErrors = $_SESSION['error'];
        return $this->renderView('index', ['list' => $this->getKeyErrors()]);
    }

    protected function _postLogin()
    {
        $params = [
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];
        $valid = $this->getModel()->validateLogin($params);
        if (!$valid) {
            $this->setSession('error', $this->getModel()->getErrors());
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

    //facebook not done

//    public function getAccessTokenWithCode($code)
//    {
//        $endpoint = "https://graph.facebook.com/v7.0/oauth/access_token";
//
//        $params = [
//            'client_id' => "711452766301403",
//            'client_secret' => "acbf4d69e0144d73640c84f42de1d136",
//            'redirect_uri' => "https://quanly.com/login/index",
//            'code' => $code
//        ];
//        return $this->makeFacebookApiCall($endpoint, $params);
//    }
//
//    public function makeFacebookApiCall($endpoint, $params)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $endpoint . '?' . http_build_query($params));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//
//        $fbResponse = curl_exec($ch);
//        $fbResponse = json_decode($fbResponse, TRUE);
//        curl_close($ch);
//
//        return array(
//            'endpoint' => $endpoint,
//            'params' => $params,
//            'has_error' => isset($fbResponse['error']) ? TRUE : FALSE,
//            'error_message' => isset($fbResponse['error']) ? $fbResponse['error']['message'] : '',
//            'fb_response' => $fbResponse,
//        );
//
//    }
//
//    function testCode()
//    {
//        $access_token = $this->getAccessTokenWithCode($_GET['code']);
//        echo "<pre>";
//        var_dump($access_token);
//        die;
//    }


}