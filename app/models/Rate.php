<?php

include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Rate extends config implements operations
{
    private $user_id;
    private $meal_id;
    private $value;
    

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getMeal_id()
    {
        return $this->meal_id;
    }

    public function setMeal_id($meal_id)
    {
        $this->meal_id = $meal_id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }


    public function create()
    {
        $query = "INSERT INTO rates(user_id , meal_id , value)
        VALUES($this->user_id , $this->meal_id , $this->value)";

        $result = $this->runDML($query);

        return $result;
    }

    public function read()
    {
        $query = "SELECT * FROM rates WHERE user_id = $this->user_id AND meal_id = $this->meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}