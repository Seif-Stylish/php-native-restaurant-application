<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Meal_order extends config implements operations
{
    private $meal_id;
    private $order_id;
    private $quantity;
    private $price;

    public function getMeal_id()
    {
        return $this->meal_id;
    }

    public function setMeal_id($meal_id)
    {
        $this->meal_id = $meal_id;
    }

    public function getOrder_id()
    {
        return $this->order_id;
    }

    public function setOrder_id($order_id)
    {
        $this->order_id = $order_id;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }


    public function create()
    {
        $query = "INSERT into meals_orders(meal_id , order_id , quantity , price)
        VALUES($this->meal_id , $this->order_id , $this->quantity , $this->price)";

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

    }
}