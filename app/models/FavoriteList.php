<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class FavoriteList extends config implements operations
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

    public function isAddedToFavoriteList()
    {
        $query = "SELECT * FROM favorite_list
        WHERE user_id = $this->user_id AND meal_id = $this->meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getFavoriteMealsForUser()
    {
        $query = "SELECT m.id , m.name_en as 'meal_name' , m.image , c.name_en as 'category_name' , s.name_en as 'subcategory_name'
        from users u , meals m , favorite_list f , categories c , subcategories s
        where f.user_id = u.id and f.meal_id = m.id and f.user_id = $this->user_id and c.id = s.category_id and s.id = m.subcategory_id";

        $result = $this->runDQL($query);

        return $result;
    }


    function create()
    {
        $query = "INSERT INTO favorite_list(user_id , meal_id)
        VALUES($this->user_id , $this->meal_id)";

        $result = $this->runDML($query);

        return $result;
    }

    function read()
    {
        
    }

    function update()
    {

    }

    function delete()
    {
        $query = "DELETE FROM favorite_list WHERE
        user_id = $this->user_id AND meal_id = $this->meal_id";

        $result = $this->runDML($query);

        return $result;
    }
}