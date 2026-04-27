<?php
class db{
    
    function databaseConnection() {
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "registrationdatabase";

        $connection = mysqli_connect($host, $username, $password, $dbname);
        if($connection->connect_error){
            die("Connection failed: " . $connection->connect_error);
        }
        return $connection;
    }
    
    function signup($name, $password, $email, $website, $comment, $gender) {
        $connection = $this->databaseConnection();
        $tablename = "users";
        $sql = "INSERT INTO ".$tablename."(name, password, email, website, comment, gender) VALUES ('".$name."','".$password."','".$email."','".$website."','".$comment."','".$gender."')";
        $result = $connection->query($sql);
        $connection->close();
        return $result;
    }
}



?>