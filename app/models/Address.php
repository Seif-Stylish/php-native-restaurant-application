<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class Address extends config implements operations
{
    private $user_id;
    private $address;
    private $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function addressExists()
    {
        $query = "SELECT * FROM addresses WHERE user_id = $this->user_id AND address = '$this->address'";

        $result = $this->runDQL($query);

        return $result;
    }

    public function getUserAddresses()
    {
        $query = "SELECT * FROM addresses WHERE user_id = $this->user_id";

        $result = $this->runDQL($query);

        return $result;
    }

    public function create()
    {
        $query = "INSERT INTO addresses(user_id , address)
        VALUES($this->user_id , '$this->address')";

        $result = $this->runDQL($query);

        return $result;
    }

    public function read()
    {
        $query = "SELECT * FROM addresses WHERE user_id = $this->user_id";

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