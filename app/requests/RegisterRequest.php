<?php

include_once __DIR__ ."\..\database\config.php";

class Validation
{
    public function isRequired($input)
    {
        return (empty($input)) ? true : false;
    }

    public function regex($input , $pattern)
    {
        return (preg_match($pattern , $input)) ? true : false;
    }

    
    public function isUnique($tableName , $column , $inputValue)
    {
        $config = new config();

        $query = "SELECT * from $tableName where $column = '$inputValue'";

        $result = $config->runDQL($query);

        return $result->num_rows > 0 ? false : true;

        //print_r($result);die;

        //return (empty($result)) ? true : false;
    }

    public function userExists($email)
    {
        $config = new config();

        $query = "SELECT * from users WHERE email = '$email'";

        $result = $config->runDQL($query);

        return $result;
    }
    

    public function isconfirmed($input , $inputConfirmation)
    {
        return ($input == $inputConfirmation) ? true : false;
    }
}