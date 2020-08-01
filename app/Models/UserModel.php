<?php

class UserModel extends BaseModel
{

    protected $_tableName = 'user';

    public function getTableName() {
        return $this->_tableName;
    }

//    public function _validator() {
//        return Validation::class;
//    }
}