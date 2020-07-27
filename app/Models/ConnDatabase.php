<?php
include "./app/Configs/configs.php";
include "./app/Common/CommonFuntion.php";

class ConnDatabase
{

    public function connect()
    {
//        $con = new mysqli(getConfig('server_name'), getConfig('user_name'), getConfig('password'),
//           getConfig('database') , getConfig('port') );

        $con = new mysqli(HOST,
            USER,
            PASSWORD,
            DATABASE_NAME,
            PORT);
        return $con;
    }

}

?>
