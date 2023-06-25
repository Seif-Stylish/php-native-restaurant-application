<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Category extends config implements operations
{
    private $id;
    private $name_en;
    private $name_ar;
    private $image;
    private $status;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCategoryBySubcategory()
    {
        $query = "SELECT c.name_en FROM categories c , subcategories sub WHERE c.id = sub.category_id
        AND sub.id = $this->id
        ";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getNumberOfSubCategoriesPerCategory()
    {
        $query = "SELECT c.id as 'category_id' , c.name_en as 'category_name' , count(c.id) as 'no_of_subcategories' from categories c , subcategories s
        where c.id = s.category_id and c.id = $this->id
        group by c.id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getNumberOfMealsPerCategory()
    {
        $query = "SELECT c.id as 'category_id' , c.name_en as 'category_name' , count(c.id) as 'no_of_meals_per_category'
        from categories c , subcategories s , meals m
        where c.id = s.category_id and s.id = m.subcategory_id and c.id = $this->id
        group by c.id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {

    }

    public function read()
    {
        $query = "SELECT * FROM categories";

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