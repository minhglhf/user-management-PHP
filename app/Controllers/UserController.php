<?php

class UserController extends BaseController
{

    protected $renderErrors = null;

    public function index()
    {
        $this->deleteError();
        return $this->renderView();
    }

    public function showUser()
    {
        return $this->renderView("showUser", ['list' => $this->getModel()->getList("0")]);
    }

    public function create()
    {

        if (isset($_POST['create'])) {
            return $this->_postCreate();
        }
        if (isset($_SESSION['error'])) $this->_keyErrors = $_SESSION['error'];
        return $this->renderView('info', ['listCreate' => $this->getKeyErrors()]);
    }

    protected function _postCreate()
    {
        $params = $this->getFormData();
        $valid = $this->getModel()->validateCreate($params);
        if (!$valid) {
            $this->setSession('error', $this->getModel()->getErrors());
            return $this->redirect('user/create');
        }
        $this->deleteSession('error');
        return $this->getModel()->setData($params);
    }


    public function search()
    {
        if (isset($_POST['selectUser'])) {
            if (isset($_POST['userSelected'])) {
                return $this->renderView("info");
            }
        }
        if (isset($_POST['search'])) {
            return $this->renderView("info", ['list' => $this->getModel()->searchData($this->getFormData())]);
        }
        return $this->renderView("info");
    }

    public function getFormData()
    {
        return $this->getModel()->searchFrom();
    }

//    public function getDataId()
//    {
//        if (isset($_POST['selectUser'])) {
//            if (isset($_POST['userSelected'])) {
//                return $_POST['userSelected'];
//            }
//        }
//        return $this->renderView("accessUser", ['list' => $this->getModel()->getList("0")]);
//    }

    public function update($dataId)
    {
        return $this->editUser($this->getModel()->getCurrentUserId($_SESSION['login']['email'])['id']);

    }

    public function edit()
    {
        $dataInfo = $this->getModel()->getRecordData($_SESSION['selectId'], '0');
        if (isset($_POST['updateData'])) {
            return $this->getModel()->passData($dataInfo);
        }
        return $this->renderView("updateUser", ['list' => $dataInfo]);
    }


    public function delete()
    {
        return $this->getModel()->deleteData($_SESSION['selectId']);
    }

    public function restore()
    {
        //$this->checkRole();
        if (isset($_POST['selectUser'])) {
            if (isset($_POST['userSelected'])) {
                return $this->getModel()->restoreData($_POST['userSelected']);
            }
        }
        return $this->renderView("accessUser", ['list' => $this->getModel()->getList("1")]);
    }



//    public function callback()
//    {
//        $app_id = "711452766301403";
//        $app_secret = "acbf4d69e0144d73640c84f42de1d136";
//        $redirect_uri = urlencode("https://quanly.com/user/callback");
//
//        // Get code value
//        $code = $_GET['code'];
//
//        // Get access token info
//        $facebook_access_token_uri = "https://graph.facebook.com/v7.0/oauth/access_token?
//        client_id=$app_id&
//        redirect_uri=$redirect_uri&
//        client_secret=$app_secret&code=$code";
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $facebook_access_token_uri);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
//
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        // Get access token
//        $aResponse = json_decode($response);
//        $access_token = $aResponse->access_token;
//
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?fields=id,name,email&access_token=$access_token");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        $user = json_decode($response);
//        $_SESSION['user_login'] = true;
//        $_SESSION['user_name'] = $user->name;
//
//        echo "Wellcome ". $user->name ."!";
//    }


}

?>