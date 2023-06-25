<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";


class Like extends config implements operations
{
    private $user_id;
    private $meal_id;
    

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

    public function isLiked()
    {
        $query = "SELECT * FROM likes WHERE user_id = $this->user_id AND meal_id = $this->meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getNumberOfLikes()
    {
        $query = "SELECT l.meal_id , count(l.meal_id) AS 'number_of_likes' FROM likes l
        WHERE l.meal_id = $this->meal_id
        GROUP BY l.meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {
        $query = "INSERT INTO likes(user_id , meal_id)
        VALUES($this->user_id , $this->meal_id)
        ";

        $result = $this->runDML($query);

        return $result;
    }

    public function read()
    {
        
    }

    public function update()
    {

    }

    public function delete()
    {
        $query = "DELETE FROM likes WHERE user_id = $this->user_id AND meal_id = $this->meal_id";

        $result = $this->runDML($query);

        return $result;
    }
}