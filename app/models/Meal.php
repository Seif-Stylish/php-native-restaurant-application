<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Meal extends config implements operations
{
    private $id;
    private $name_en;
    private $name_ar;
    private $desc_en;
    private $desc_ar;
    private $price;
    private $quantity;
    private $image;
    private $meal_status;
    private $subcategory_id;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName_en()
    {
        return $this->name_en;
    }

    public function setName_en($name_en)
    {
        $this->name_en = $name_en;
    }

    public function getName_ar()
    {
        return $this->name_ar;
    }

    public function setName_ar($name_ar)
    {
        $this->name_ar = $name_ar;
    }

    public function getDesc_en()
    {
        return $this->desc_en;
    }

    public function setDesc_en($desc_en)
    {
        $this->desc_en = $desc_en;
    }

    public function getDesc_ar()
    {
        return $this->desc_ar;
    }

    public function setDesc_ar($desc_ar)
    {
        $this->desc_ar = $desc_ar;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getMeal_status()
    {
        return $this->meal_status;
    }

    public function setMeal_status($meal_status)
    {
        $this->meal_status = $meal_status;
    }

    public function getSubcategory_id()
    {
        return $this->subcategory_id;
    }

    public function setSubcategory_id($subcategory_id)
    {
        $this->subcategory_id = $subcategory_id;
    }

    public function getMealBySubcategory()
    {
        $query = "SELECT * FROM meals WHERE subcategory_id = $this->subcategory_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getMealRate()
    {
        $query = "SELECT meal_id , meals.name_en , avg(value) as 'total_rate' FROM rates , meals
        WHERE meals.id = rates.meal_id AND meal_id = $this->id
        GROUP BY meal_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getSubcategoryByMeal()
    {
        $query = "SELECT sub.id , sub.name_en FROM meals m , subcategories sub
        WHERE sub.id = m.subcategory_id AND m.id = $this->id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getMealDetails(){
        $query = "SELECT c.id as 'category-id' , c.name_en as 'category_name' , s.id as 'subcategory_id' , s.name_en as 'subcategory_name' , m.id as 'meal_id' , m.name_en as 'meal_name' , m.image , m.price , m.created_at from
        categories c , subcategories s , meals m
        where c.id = s.category_id and s.id = m.subcategory_id and m.id = $this->id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getMostLikedMeals()
    {
        $query = "SELECT l.meal_id , m.name_en as 'meal_name' , c.name_en as 'category_name' , s.name_en as 'subcategory_name' , m.desc_en , m.image , count(l.meal_id) as 'number_of_likes'
        from likes l , meals m , categories c , subcategories s
        where m.id = l.meal_id and c.id = s.category_id and s.id = m.subcategory_id
        group by l.meal_id
        order by count(l.meal_id) desc";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getHighlyRatedMeals()
    {
        $query = "SELECT meal_id , meals.name_en as 'meal_name' , meals.image , categories.name_en as 'category_name' , avg(value) as 'total_rate' FROM rates , meals , categories , subcategories
        WHERE meals.id = rates.meal_id AND categories.id = subcategories.category_id AND subcategories.id = meals.subcategory_id
        GROUP BY meal_id
        ORDER BY total_rate DESC";

        $result = $this->runDQL($query);

        return $result;
    }
    

    public function create()
    {

    }

    public function read()
    {
        $query = "SELECT * FROM meals WHERE id = $this->id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getAllMeals()
    {
        $query = "SELECT * FROM meals";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getAllMealsWithFullDetails()
    {
        $query = "SELECT c.id as 'category-id' , c.name_en as 'category_name' , s.id as 'subcategory_id' , s.name_en as 'subcategory_name' , m.id as 'meal_id' , m.name_en as 'meal_name' , m.image , m.price , m.created_at from
        categories c , subcategories s , meals m
        where c.id = s.category_id and s.id = m.subcategory_id AND s.id = $this->subcategory_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function makeAnOrder($categoryname)
    {
        $query = "SELECT m.id as 'meal_id' , m.name_en as 'meal_name' , m.quantity , m.image , c.name_en as 'category_name'
        from meals m , categories c , subcategories s
        where c.id = s.category_id and s.id = m.subcategory_id and c.name_en = '$categoryname'";

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