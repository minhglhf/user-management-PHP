<?php

class UserController extends BaseController
{


    public function index()
    {
        $this->renderView();
    }

    public function showUser()
    {
        $rowlistUser = $this->getModel()->getList("0");
        $this->renderView("showUser", ['list' => $rowlistUser]);
    }

    public function checkDataFromInfo()
    {
        $this->renderView("info");
        if (isset($_POST['oke'])) {
            $data = array(
                'permission_access' => $_POST['permission_access'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'address' => $_POST['address'],
                'sex' => $_POST['sex'],
                'name' => $_POST['name'],
                'birth' => $_POST['birth']
            );
            return $data;
        }
        return 0;
    }

    public function create()
    {
        $data = $this->checkDataFromInfo();
        if (!empty($data)) {
            $x = $this->getModel()->setData($data);
        }
    }


    public function search()
    {
        $data = $this->checkDataFromInfo();
        if (!empty($data)) {
            $x = $this->getModel()->searchData($data);
            $this->renderView("showUser", ['list' => $x]);
        }

    }

    public function accessData()
    {
        $selectData = "";
        $data = $this->checkDataFromInfo();
        if (!empty($data)) {
            $x = $this->getModel()->searchData($data);
            $this->renderView("accessUser", ['list' => $x]);
        }
        if (isset($_POST['select'])) {
            if (isset($_POST['userSelected'])) {
                $selectData = $_POST['userSelected'];

            }
        }
        return $selectData;
    }

    public function delete()
    {
        $x = null;
        $x = $this->accessData();
        if ($x != null) {
            $this->getModel()->deleteData($x);
        }
    }

    public function update()
    {
        $data = [];
        $x = null;
        $x = $this->accessData();
        if ($x != null) {
            $y = $this->getModel()->getRecordInfo($x);
            $this->renderView("updateUser", ['list' => $y]);
        }
        if(isset($_POST['updateData'])){
            $data = array(
                'id' => $_POST['value0'],
                'permission_access' => $_POST['value1'],
                'email' => $_POST['value2'],
                'password' => $_POST['value3'],
                'address' => $_POST['value4'],
                'sex' => $_POST['value5'],
                'name' => $_POST['value6'],
                'birth' => $_POST['value7']
            );
            $this->getModel()->updateData($data);
        }

    }


    public function restore()
    {
        echo "<h2>delete lists</h2>";
        $list = $this->getModel()->getList("1");
        $this->renderView("accessUser", ['list' => $list]);
        if (isset($_POST['select'])) {
            if (isset($_POST['userSelected'])) {
                $selectData = $_POST['userSelected'];
                $this->getModel()->restoreData($selectData);
            }
        }
    }


}

?>