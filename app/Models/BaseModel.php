<?php

class BaseModel
{
    protected $_connection = null;
    protected $_tableName = "user";
    protected $_validation = null;
    protected $_errors = [];

    public function __construct()
    {
        if (!$this->getConnection()) {
            $connection = $this->connect();
            $this->setConnection($connection);
        }
//        $Validation = $this->loadValidation('Validation');
//        $this->setValidation($Validation);
    }

    public function setValidation($validation)
    {
        $this->_validation = $validation;
    }

    public function getValidation()
    {
        return $this->_validation;
    }

//    public function loadValidation($filename)
//    {
//        require_once('./app/Validation/' . $filename . '.php');
//        return new $filename;
//    }

    public function setConnection($connection)
    {
        $this->_connection = $connection;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function connect()
    {
//        $con = new mysqli(getConfig('server_name'), getConfig('user_name'), getConfig('password'),
//           getConfig('database') , getConfig('port') );
        $con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME, PORT);
        return $con;
    }

    public function getTableName()
    {
        return $this->_tableName;
    }

    public function exec($sql)
    {
        if ($sql != "") {
            return mysqli_query($this->getConnection(), $sql);
        }
    }


    public function getList($delete_flag)
    {
        $list = [];
        $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = " . $delete_flag;
        $result = $this->exec($sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }

    public function getAdminEmail()
    {
        $list = [];
        $sql = "SELECT email FROM {$this->getTableName()} WHERE delete_flag = '0' AND role = '2' OR role = '1' ";
        $result = $this->exec($sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }

    public function getSqlConditions($data, $action)
    {
        $sqlTmp = [];
        foreach ($data as $field => $value) {
            if ($action == 'showUser') $sqlTmp[] = $field . ' = \'' . $value . '\'';
            if ($action == 'create') $sqlTmp[] = '\'' . $value . '\'';
            if ($action == 'search' && $value != "") $sqlTmp[] = $field . ' LIKE \'%' . $value . '%\'';
            if ($action == 'update' && $value != "" && $field != "id") $sqlTmp[] = $field . ' = \'' . $value . '\'';
        }
        return $sqlTmp;
    }


    public function getData($conditions)
    {
        $sqlTmp = $this->getSqlConditions($conditions, 'showUser');
        if (!empty($sqlTmp)) {
            $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND " . implode(' AND ', $sqlTmp);
        }

        $result = $this->exec($sql);
        return mysqli_fetch_assoc($result);
    }

    public function getCurrentUserId($email)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND email = " . '\'' . $email . '\'';
        $result = $this->exec($sql);
        return $result->fetch_assoc();
    }

    public function setData($data)
    {
        $val = implode(' , ', $this->getSqlConditions($data, 'create'));
        if (isset($_POST['create'])) {
            $sql = "INSERT INTO {$this->getTableName()}(role, email, password, address, sex, name ,birth) VALUES($val)";
            $this->exec($sql);
            $this->sendMail($data);
        }
    }

    public function sendMail($createData)
    {
        $this->actionIsSuccess();
        $subject = "user created";
        $message = "an user had been created name= " . $createData['name'] . " email= " . $createData['email']
            . " password= " . $createData['password'] . " address= " . $createData['address']
            . " sex= " . $createData['sex'] . " birth= " . $createData['birth'] . "<br> " . "created by admin: "
            . $this->getCurrentUserId($_SESSION['login']['email'])['name'];

        $sqlTmp = [];
        foreach ($this->getAdminEmail() as $value) {
            $sqlTmp[] = $value['email'];
        }

        $receive = implode(' , ', $sqlTmp);

        $success = mail($receive, $subject, $message);
        if ($success) {
            echo "<br> send email success";
        } else {
            echo "<br> send email not succees";
        }
    }

    public function searchData($data)
    {
        $list = [];
        $val = implode(' AND ', $this->getSqlConditions($data, 'search'));
        if ($val == '') return $list;
        else $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND " . $val;
        $result = $this->exec($sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = $row;
            }
        }
        return $list;
    }


    public function updateData($data)
    {
        if (!empty($data)) {
            $up = implode(' , ', $this->getSqlConditions($data, 'update'));
            $sql = "UPDATE {$this->getTableName()} SET " . $up . " WHERE id = " . $data['id'];
            $this->exec($sql);
            unset($_SESSION['selectId']);
            $this->actionIsSuccess();
        }
    }

    public function actionIsSuccess()
    {
        echo "<h2>Susccessful</h2>";
        echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
    }

    public function passData($dataInfo)
    {
        if (isset($_POST['updateData'])) {
            $this->updateData([
                'id' => $_POST['value0'],
                'role' => $_POST['value1'],
                'email' => $_POST['value2'],
                'password' => $_POST['value3'],
                'address' => $_POST['value4'],
                'sex' => $_POST['value5'],
                'name' => $_POST['value6'],
                'birth' => $_POST['value7']
            ]);
        }
    }

    public function searchFrom()
    {
        return [
            'role' => $_POST['role'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'address' => $_POST['address'],
            'sex' => $_POST['sex'],
            'name' => $_POST['name'],
            'birth' => $_POST['birth']
        ];
    }

    public function deleteData($data)
    {
        $this->setDeath($data, 0, 1);
    }

    public function restoreData($data)
    {
        $this->setDeath($data, 1, 0);
    }

    public function setDeath($data, $delete_flag1, $delete_flag2)
    {
        $this->getRecordData($data, $delete_flag1);
        $sql = "UPDATE {$this->getTableName()} SET delete_flag = " . $delete_flag2 . " WHERE id = " . $data;
        $this->exec($sql);
        unset($_SESSION['selectId']);
        $this->actionIsSuccess();
    }

    public function getRecordData($dataId, $delete_flag)
    {
        if ($dataId != '') {
            $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = " . $delete_flag . " AND id = " . $dataId;
            $result = $this->exec($sql);
            return (mysqli_fetch_assoc($result));
        }

    }

    public function validateLogin($params)
    {
        if (!isset($params["email"]) || empty($params["email"])) {
            $this->setErrors('email', "* bạn chưa nhập email!!");
            return false;
        }
        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $this->setErrors('email', "* email không hợp lệ");
            return false;
        }
        if (!isset($params["password"]) || empty($params["password"])) {
            $this->setErrors('password', "* bạn chưa nhập Mật khẩu!!");
            return false;
        }
        $user = $this->getData([
            'email' => $params['email'],
            'password' => $params['password']
        ]);
        if (empty($user)) {
            $this->setErrors('password', "*Mật khẩu không chính xác!!");
            return false;
        }
        return true;
    }

    public function validateCreate($params)
    {
        if (empty($params["role"])) {
            $this->setErrors('role_create', "* role is required");
            return false;
        }
        if (isset($params["role"]) && $params['role'] != '1' && $params['role'] != '2' && $params['role'] != '3') {
            $this->setErrors('role_create', "* role chấp nhận giá trị 1,2,3");
            return false;
        }
        if (empty($params["email"])) {
            $this->setErrors('email_create', "* email is required");
            return false;
        }
        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $this->setErrors('email_create', "* email không hợp lệ");
            return false;
        }
        if (empty($params["password"])) {
            $this->setErrors('password_create', "* password is required");
            return false;
        }
        if (isset($params["password"]) && strlen($params['password']) < 8) {
            $this->setErrors('password_create', "* password cần tối thiểu 8 kí tự");
            return false;
        }
        if (isset($params["sex"]) && $params['sex'] != '1' && $params['sex'] != '0') {
            $this->setErrors('sex_create', "* giới tính chấp chận Male:0 hoặc Female:1 ");
            return false;
        }
        if (isset($params["name"]) && !preg_match("/^[a-zA-Z ]*$/",$params['name'])) {
            $this->setErrors('name_create', "* tên chỉ chấp nhận kí tự và khoảng trắng");
            return false;
        }
        if (isset($params["birth"]) && !(bool)strtotime($params['birth'])) {
            $this->setErrors('birth_create', "* không đúng định dạng YYYY-MM-DD");
            return false;
        }
        return true;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setErrors($key, $error)
    {
        $this->_errors[$key] = $error;
    }


}