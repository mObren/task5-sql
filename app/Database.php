<?php
namespace App;

class Database {


    private const DB_NAME = "nb_soft";
    private const DB_USERNAME = "root";
    private const DB_PASSWORD = "";
    private const DB_TYPE = "mysql";
    private const DB_HOST = "127.0.0.1";


    public function db_connect()
    {
        try {

            return $conn = new \PDO(self::DB_TYPE . ":host=" . self::DB_HOST .
                ";dbname=" . self::DB_NAME, self::DB_USERNAME, self::DB_PASSWORD);


        } catch(\PDOException $e) {
            die("Connection failed. Error is: " . $e->getMessage());
        }
    }

    public function read($query, $data = []) {
        $DB = $this->db_connect();
        $statement = $DB->prepare($query);
        if (count($data) == 0) {
            $statement = $DB->query($query);
        }
         $statement->execute($data);
            return $statement->fetchAll(\PDO::FETCH_OBJ);

    }
    public function write($query, $data = []) {

        $DB = $this->db_connect();
        $statement = $DB->prepare($query);
        $statement->execute($data);
    }
}
