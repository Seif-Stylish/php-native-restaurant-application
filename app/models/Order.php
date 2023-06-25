<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Order extends config implements operations
{
    private $id;
    private $payment_method;
    private $status;
    private $address_id;
    private $created_at;
    private $updated_at;
    private $delivered_at;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPayment_method()
    {
        return $this->payment_method;
    }

    public function setPayment_method($payment_method)
    {
        $this->payment_method = $payment_method;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getAddress_id()
    {
        return $this->address_id;
    }

    public function setAddress_id($address_id)
    {
        $this->address_id = $address_id;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getDelivered_at()
    {
        return $this->delivered_at;
    }

    public function setDelivered_at($delivered_at)
    {
        $this->delivered_at = $delivered_at;
    }

    public function orderDelivered()
    {
        $query = "UPDATE orders SET delivered_at = '$this->delivered_at' WHERE id = $this->id";

        $result = $this->runDML($query);

        return $result;
    }

    public function getSingleOrder()
    {
        $query = "SELECT * FROM orders WHERE id = $this->id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {
        $query = "INSERT INTO orders(payment_method , address_id)
        VALUES('$this->payment_method' , $this->address_id)";

        $result = $this->runDML($query);

        return $result;
    }

    public function read()
    {
        $query = "SELECT * FROM orders";

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