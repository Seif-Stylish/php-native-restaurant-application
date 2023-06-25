<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Comment extends config implements operations
{
    private $user_id;
    private $meal_id;
    private $user_comment;

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

    public function getUser_comment()
    {
        return $this->user_comment;
    }

    public function setUser_comment($user_comment)
    {
        $this->user_comment = $user_comment;
    }

    public function getSubcategoryByMeal()
    {
        $query = "SELECT sub.id , sub.name_en FROM meals m , subcategories sub
        WHERE sub.id = m.subcategory_id AND m.id = $this->meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getNumberOfComments()
    {
        $query = "SELECT c.meal_id , count(c.meal_id) AS 'number_of_comments' FROM comments c
        WHERE c.meal_id = $this->meal_id
        GROUP BY c.meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {
        $query = "INSERT INTO comments(user_id , meal_id , user_comment)
        VALUES($this->user_id , $this->meal_id , '$this->user_comment')";

        $result = $this->runDML($query);

        return $result;
    }

    public function read()
    {
        $query = "SELECT m.id AS 'meal_id' , u.first_name , u.last_name , c.user_comment AS 'comment'
        FROM meals m , comments c , users u
        WHERE c.user_id = u.id AND c.meal_id = m.id AND m.id = $this->meal_id";

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