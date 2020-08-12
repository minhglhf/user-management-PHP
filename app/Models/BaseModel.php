<?php

class BaseModel
{
    protected $_connection = null;
    protected $_validation = null;
    protected static $_tableName = null;
    protected $_errors = [];

    public function __construct()
    {
        UserModel::$_tableName;
        if (!$this->getConnection()) {
            $connection = $this->connect();
            $this->setConnection($connection);
        }
    }

    public function setValidation($validation)
    {
        $this->_validation = $validation;
    }

    public function getValidation()
    {
        return $this->_validation;
    }

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
        return new mysqli(HOST, USER, PASSWORD, DATABASE_NAME, PORT);
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setErrors($key, $error)
    {
        $this->_errors[$key] = $error;
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

    public function getRole()
    {
        return [
            '1' => 'Super Admin',
            '2' => 'Admin',
            '3' => 'User'
        ];
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
            if ($action == 'create') {
                if ($field == 'password') $sqlTmp[] = '\'' . password_hash($value, PASSWORD_BCRYPT) . '\'';
                else $sqlTmp[] = '\'' . $value . '\'';
            }
            if ($action == 'search' && $value != "") $sqlTmp[] = $field . ' LIKE \'%' . $value . '%\'';
            if ($action == 'update' && $value != "" && $field != "id") {
                if ($field == 'password') $sqlTmp[] = $field . ' = \'' . password_hash($value, PASSWORD_BCRYPT) . '\'';
                else $sqlTmp[] = $field . ' = \'' . $value . '\'';
            }
        }
        return $sqlTmp;
    }


    public function getData($conditions)
    {
        $sql = "SELECT password FROM {$this->getTableName()} WHERE delete_flag = '0' AND email = " . ' \'' . $conditions['email'] . '\'';
        $result = $this->exec($sql);

        return password_verify($conditions['password'], mysqli_fetch_assoc($result)['password']);
    }

    public function getDataEmail($conditions)
    {
        $sql = "SELECT password FROM {$this->getTableName()} WHERE delete_flag = '0' AND email = " . ' \'' . $conditions['email'] . '\'';
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
                'id' => $_POST['id'],
                'role' => $_POST['role'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'address' => $_POST['address'],
                'sex' => $_POST['sex'],
                'name' => $_POST['name'],
                'birth' => $_POST['birth']
            ]);
        }
    }

    public function searchFrom($id)
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
        return $this->setDeath($data, 0, 1);
    }

    public function restoreData($data)
    {
        return $this->setDeath($data, 1, 0);
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
        $userMail = $this->getDataEmail(['email' => $params['email']]);
        $user = $this->getData([
            'email' => $params['email'],
            'password' => $params['password']
        ]);
        if (!isset($params["email"]) || empty($params["email"]) || $params['email'] == '') {
            $this->setErrors('email', "* bạn chưa nhập email!!");
            return false;
        }
        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $this->setErrors('email', "* email không hợp lệ");
            return false;
        }
        if ($userMail) {
            $this->setErrors('save_email', $params["email"]);
        }
        if (!isset($params["password"]) || empty($params["password"]) || $params['password'] == '') {
            $this->setErrors('password', "* bạn chưa nhập Mật khẩu!!");
            return false;
        }
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
        if (isset($params["role"]) && $params['role'] != '1' && $params['role'] != '2' && $params['role'] != '3' && $params['role'] != "") {
            $this->setErrors('role_create', "* role chấp nhận giá trị 1,2,3");
            return false;
        } else $this->setErrors('role_accept', $params["role"]);
        if (empty($params["email"])) {
            $this->setErrors('email_create', "* email is required");
            return false;
        }
        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $this->setErrors('email_create', "* email không hợp lệ");
            return false;
        }
        if (isset($params["email"]) && $params['email'] != "" && $this->checkDuplicateEmail($params['email'])) {
            $this->setErrors('email_create', "* email đã tồn tại");
            return false;
        } else $this->setErrors('email_accept', $params["email"]);
        if (empty($params["password"])) {
            $this->setErrors('password_create', "* password is required");
            return false;
        }
        if (isset($params["password"]) && strlen($params['password']) < 8) {
            $this->setErrors('password_create', "* password cần tối thiểu 8 kí tự");
            return false;
        } else $this->setErrors('password_accept', $params["password"]);
        if (isset($params["sex"]) && $params['sex'] != '1' && $params['sex'] != '0' && $params['sex'] != "") {
            $this->setErrors('sex_create', "* giới tính chấp chận Male:0 hoặc Female:1 ");
            return false;
        } else $this->setErrors('sex_accept', $params["sex"]);
        if (isset($params["name"]) && !preg_match("/^[a-zA-Z ]*$/", $params['name']) && $params['name'] != "") {
            $this->setErrors('name_create', "* tên chỉ chấp nhận kí tự và khoảng trắng");
            return false;
        } else $this->setErrors('name_accept', $params["name"]);
        if (isset($params["birth"]) && !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $params["birth"]) && $params['birth'] != "") {
            $this->setErrors('birth_create', "* không đúng định dạng YYYY-MM-DD");
            return false;
        } else $this->setErrors('birth_accept', $params["birth"]);
        return true;
    }

    public function validateUpdate($params)
    {
        if (isset($params["role"]) && $params['role'] != '1' && $params['role'] != '2' && $params['role'] != '3' && $params['role'] != "") {
            $this->setErrors('role_create', "* role chấp nhận giá trị 1,2,3");
            return false;
        }
        if (isset($params["email"]) && $params['email'] != "" && !filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $this->setErrors('email_create', "* email không hợp lệ");
            return false;
        }
        if (isset($params["password"]) && $params['password'] != "" && strlen($params['password']) < 8) {
            $this->setErrors('password_create', "* password cần tối thiểu 8 kí tự");
            return false;
        }
        if (isset($params["sex"]) && $params['sex'] != '1' && $params['sex'] != '0' && $params['sex'] != "") {
            $this->setErrors('sex_create', "* giới tính chấp chận Male:0 hoặc Female:1 ");
            return false;
        }
        if (isset($params["name"]) && !preg_match("/^[a-zA-Z ]*$/", $params['name']) && $params['name'] != "") {
            $this->setErrors('name_create', "* tên chỉ chấp nhận kí tự và khoảng trắng");
            return false;
        } else $this->setErrors('name_accept', $params["name"]);
        if (isset($params["birth"]) && !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $params["birth"]) && $params['birth'] != "") {
            $this->setErrors('birth_create', "* không đúng định dạng YYYY-MM-DD");
            return false;
        }
        return true;
    }

    public function checkDuplicateEmail($emailInput)
    {
        $sql = "SELECT email FROM {$this->getTableName()} WHERE delete_flag = '0' AND email = " . ' \'' . $emailInput . '\'';
        $result = $this->exec($sql);
        if (mysqli_num_rows($result) > 0)
            return 1;
    }

    public function deniedAccess()
    {
        echo "Access Denied - Not your role! <br>";
        echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
        die;
    }

}