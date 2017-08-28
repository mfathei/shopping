<?php


class Database
{

    function getMySQLConnection($dbname)
    {

        $host = "localhost";
        $user = "root";
        $pass = "root";

        try {
            $dbh = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
            return $dbh;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

}

class Result
{
    public $records = null;
}

class Item
{
    public $id;
    public $name;
    public $desc;
    public $price;
    public $quantity;
}
