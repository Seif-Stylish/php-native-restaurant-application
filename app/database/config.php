<?php

class config
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $databasename = "restaurant";
    private $con;

    public function __construct()
    {
        $this->con = new mysqli($this->hostname , $this->username , $this->password , $this->databasename);

        /*
        if($con->connect_error)
        {
            die("Connection failed: ". $con->connect_error);
        }
        echo "Connected successfully";
        */
    }

    public function runDML($query)
    {
        $result = $this->con->query($query);

        if($result)
        {
            return true;
        }
        return false;
    }

    public function runDQL($query)
    {
        $result = $this->con->query($query);

        if($result)
        {
            return $result;
        }
        return [];
    }
}

//$x = new config();