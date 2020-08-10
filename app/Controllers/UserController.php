<?php

class UserController extends BaseController
{

    public function index()
    {
        $this->deleteError();
        if (!$this->isLoggedIn()) {
            return $this->redirect('login/index');
        }
        return $this->renderView('index', ['roles' => $this->getModel()->getRole()]);
    }

    public function showUser()
    {
        $this->checkRole();
        return $this->renderView("showUser", ['list' => $this->getModel()->getList("0")]);
    }

    public function create()
    {
        $this->checkRole();
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
        $this->checkRole();
        if (isset($_POST['selectUser'])) {
            if (!isset($_POST['userSelected'])) {
                return $this->refresh();
            }
        }
        if (isset($_POST['search'])) {
            return $this->renderView("info", ['list' => $this->getModel()->searchData($this->getFormData())]);
        }
        return $this->renderView("info");
    }

    public function getFormData($id = null)
    {
        return $this->getModel()->searchFrom($id);
    }

    public function update($dataId)
    {
        $this->checkRole();
        return $this->editData( $this->getModel()->getCurrentUserId($_SESSION['login']['email'])['id']);
    }

    public function edit()
    {
        if($this->checkRole()){
            if($this->checkRoleDetail($this->getModel()->getRecordData($_SESSION['selectId'], '0')['role'])){
                return $this->editData($_SESSION['selectId']);
            }
        }
        return $this->getModel()->deniedAccess();
    }


    public function editData($dataId){
        $dataInfo = $this->getModel()->getRecordData($dataId, '0');
        if (isset($_POST['updateData'])) {
            return $this->_postEditData();
        }
        if (isset($_SESSION['error'])) $this->_keyErrors = $_SESSION['error'];
        return $this->renderView("updateUser", ['list' => $dataInfo, 'listCreate' => $this->getKeyErrors()]);
    }

    public function _postEditData(){
        $params = $this->getFormData('getId');
        $valid = $this->getModel()->validateUpdate($params);
        if (!$valid) {
            $this->setSession('error', $this->getModel()->getErrors());
            return $this->redirect('user/' . $this->getCurrentActionName());
        }
        $this->deleteSession('error');
        return $this->getModel()->passData($params);
    }


    public function delete()
    {
        if($this->checkRole()){
            if($this->checkRoleDetail($this->getModel()->getRecordData($_SESSION['selectId'], '0')['role'])){
                $this->getModel()->deleteData($_SESSION['selectId']);
            }
        }
        return $this->getModel()->deniedAccess();
    }

    public function restore()
    {
        $this->checkRole();
        if (isset($_POST['selectUser'])) {
            if (isset($_POST['userSelected'])) {
                return $this->getModel()->restoreData($_POST['userSelected']);
            }
        }
        return $this->renderView("info", ['list' => $this->getModel()->getList("1")]);
    }
}

?>