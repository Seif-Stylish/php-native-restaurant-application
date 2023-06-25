<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Subcategory extends config implements operations
{
    private $id;
    private $name_en;
    private $name_ar;
    private $image;
    private $status;
    private $category_id;

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

    public function getCategory_id()
    {
        return $this->category_id;
    }

    public function setCategory_id($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getSubCategoryByCategory()
    {
        $query = "SELECT * FROM subcategories WHERE category_id = $this->category_id";

        $result = $this->runDQL($query);
        
        return $result;
    }

    public function getNumberOfMealsPerSubcategory()
    {
        $query = "SELECT s.id as 'subcategory_id' , s.name_en as 'subcategory_name' , count(s.id) as 'no_of_meals_per_subcategory'
        from meals m , subcategories s
        where s.id = m.subcategory_id and s.id = $this->id
        group by s.id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getSingleSubcategory()
    {
        $query = "SELECT * FROM subcategories WHERE id = $this->id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {

    }

    public function read()
    {
        $query = "SELECT * FROM subcategories";

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