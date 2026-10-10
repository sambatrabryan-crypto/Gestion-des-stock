<?php
class connect extends PDO{


    const HOST="localhost";
    const DB="outils";
    const USER="root";
    const PSW="";

    public function __construct(){
        try {
            //code...
            parent::__construct("mysql:dbname=".self::DB.";host=".self::HOST,self::USER,self::PSW);
            
        } catch (PDOException $e) {
            echo $e->getMessage()." ".$e->getFile()." ".$e->getLine();
            //throw $th;
        }
    }
}

?>