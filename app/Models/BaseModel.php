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
        $sqlTmp = [];
        foreach ($data as $field => $value) {
            $sqlTmp[] = '\'' . $value . '\'';
        }

        $val = implode(' , ', $sqlTmp);

        if (isset($_POST['oke'])) {
            $sql = "INSERT INTO {$this->getTableName()}(permission_access, email, password, address, sex, name ,birth)
                    VALUES($val)";

            $this->exec($sql);
            echo "<h2>Insert Data Susccessful</h2>";
            echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
        }

    }

    public function searchData($data)
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

    public function deleteData($data)
    {
        $sql = "UPDATE {$this->getTableName()} SET delete_flag = '1' WHERE id = " . $data;
        $this->exec($sql);
        echo "<h2>Delete Susccessful</h2>";
        echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
    }

    public function getRecordInfo($data)
    {
        $list = [];
        $sql = "SELECT * FROM {$this->getTableName()} WHERE delete_flag = '0' AND id = " . $data;
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
            $x = null;
            $sqlTmp = [];
            foreach ($data as $field => $value) {
                if ($field == "id") {
                    $x = $value;
                }
                if ($value != "" && $field != "id") $sqlTmp[] = $field . ' = \'' . $value . '\'';
            }


            $up = implode(' , ', $sqlTmp);
            $sql = "UPDATE {$this->getTableName()} SET " . $up . "WHERE id = " . $x;

            $this->exec($sql);
            echo "<h2>Update Susccessful</h2>";
            echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
        }
    }


    public function restoreData($data)
    {
        $sql = "UPDATE {$this->getTableName()} SET delete_flag = '0' WHERE id = " . $data;
        $this->exec($sql);
        echo "<h2>Restore Susccessful</h2>";
        echo "<a href = \" ../user/index\" ><input type = \"submit\" name = \"backToHomePage\" value = \"back to home pager\" ></a >";
    }


}