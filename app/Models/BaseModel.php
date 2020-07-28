<?php


class BaseModel extends ConnDatabase
{
    protected $_connection = null;
    protected $_tableName = "user";

    public function setConnection()
    {
        $this->_connection = $this->connect();
    }

    public function getConnection()
    {
        if (!$this->connect()) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $this->connect();
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


    public function getData($conditions)
    {
        $sql = '';
        $sqlTmp = [];
        foreach ($conditions as $field => $value) {
            $sqlTmp[] = $field . ' = \'' . $value . '\'';
        }

        if (!empty($sqlTmp)) {
            $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND " . implode(' AND ', $sqlTmp);
        }

        $result = $this->exec($sql);
        return mysqli_fetch_assoc($result);

    }

    public function setData($data)
    {
        $roleFlag = 1;
        $sqlTmp = [];
        foreach ($data as $field => $value) {
            if (($_SESSION['role'] == '2' && ($value == '2' || $value == '1')) || ($_SESSION['role'] == '1' && $value == '1')) $roleFlag = 0;
                $sqlTmp[] = '\'' . $value . '\'';
            }

            $val = implode(' , ', $sqlTmp);

            if (isset($_POST['oke'])) {
                $sql = "INSERT INTO {$this->getTableName()}(permission_access, email, password, address, sex, name ,birth)
                    VALUES($val)";

                if ($roleFlag == 0) {
                    echo "Not your role! cannot create admin or superAdmin<br>";
                    echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
                } else {
                    $this->exec($sql);
                    echo "<h2>Insert Data Susccessful</h2>";
                    echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
                }
            }
        }

        public
        function searchData($data)
        {
            $sqlTmp = [];
            $list = [];
            foreach ($data as $field => $value) {
                if ($value != "") $sqlTmp[] = $field . ' LIKE \'%' . $value . '%\'';
            }

            $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND " . implode(' AND ', $sqlTmp);


            $result = $this->exec($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $list[] = $row;
                }
            }
            return $list;
        }


        public
        function updateData($data)
        {
            if (!empty($data)) {

                $x = null;
                $role = null;
                $sqlTmp = [];
                foreach ($data as $field => $value) {
                    if ($field == "id") $x = $value;
                    if ($value != "" && $field != "id") $sqlTmp[] = $field . ' = \'' . $value . '\'';
                }
                $y = $this->getRecordInfo($x, '0');
                $roleDefault = $y[0]['permission_access'];
                $idDefault = $y[0]['id'];

                if (($_SESSION['role'] == '1' && $idDefault != '1') || ($_SESSION['role'] == '2' && $roleDefault == '3') || isset($_POST['updateMyData'])) {
                    $up = implode(' , ', $sqlTmp);
                    $sql = "UPDATE {$this->getTableName()} SET " . $up . "WHERE id = " . $x;
                    $this->exec($sql);
                    echo "<h2>Update Susccessful</h2>";
                    echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
                } else {
                    echo "Not your role! cannot edit admin or superAdmin<br>";
                    echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
                }

            }
        }

        public
        function deleteData($data)
        {
            $this->setDeath($data, 0, 1);
        }

        public
        function restoreData($data)
        {
            $this->setDeath($data, 1, 0);
        }

        public
        function setDeath($data, $delete_flag1, $delete_flag2)
        {
            $x = $this->getRecordInfo($data, $delete_flag1);
            $role = $x[0]['permission_access'];

            if (($_SESSION['role'] == '2' && ($role == '2' || $role == '1')) || ($_SESSION['role'] == '1' && $role == '1')) {
                echo "Not your role! cannot restore admin or superAdmin<br>";
                echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
            } else {
                $sql = "UPDATE {$this->getTableName()} SET delete_flag = " . $delete_flag2 . " WHERE id = " . $data;
                $this->exec($sql);
                echo "<h2>Susccessful</h2>";
                echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
            }
        }

        public
        function getRecordInfo($data, $delete_flag)
        {
            $list = [];
            $sql = null;
            if ($_SESSION['role'] == '3' || isset($_POST['updateMyData'])) {
                $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = " . $delete_flag .
                    " AND email = " . '\'' . $_SESSION['email'] . '\'' . " AND password = " . '\'' . $_SESSION['password'] . '\'';
            } else {
                $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = " . $delete_flag . " AND id = " . $data;
            }
            $result = $this->exec($sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $list[] = $row;
                }
            }
            return $list;

        }


    }