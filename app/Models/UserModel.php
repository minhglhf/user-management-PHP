<?php

class UserModel extends BaseModel
{

    protected static $_tableName  = 'user' ;

     public static function getTableName() {

        return static::$_tableName;
    }
    /*static public function test() {
        return "test";
    }*/
//    public function _validator() {
//        return Validation::class;
//    }
}