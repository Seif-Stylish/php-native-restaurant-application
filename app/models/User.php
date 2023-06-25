<?php


include_once __DIR__ ."\..\database\config.php";
include_once __DIR__ ."\..\database\operations.php";

class User extends config implements operations
{
    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $password;
    private $gender;
    private $image;
    private $status;
    private $email_verefied_at;
    private $created_at;
    private $updated_at;
    private $code;
    
    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = sha1($password);
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
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

    public function getEmail_verefied_at()
    {
        return $this->email_verefied_at;
    }

    public function setEmail_verefied_at($email_verefied_at)
    {
        $this->email_verefied_at = $email_verefied_at;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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

    public function getSingleUser()
    {
        $query = "SELECT * FROM users WHERE email = '$this->email'";

        return $this->runDQL($query);
    }

    public function create()
    {
        $query = "insert into users(first_name , last_name , email , phone , gender , password , code)
        values('$this->first_name' , '$this->last_name' , '$this->email' , '$this->phone' , '$this->gender' , '$this->password' , $this->code)    
        ";

        return $this->runDML($query);
    }

    public function read()
    {

    }

    public function update()
    {
        $query = "";

        if(empty($this->image))
        {
            $query = "UPDATE users SET
            first_name = '$this->first_name' , last_name = '$this->last_name' , phone = '$this->phone' , gender = '$this->gender'
            WHERE email = '$this->email'";
        }
        else
        {
            $query = "UPDATE users SET
            first_name = '$this->first_name' , last_name = '$this->last_name' , phone = '$this->phone' , gender = '$this->gender',
            image = '$this->image' WHERE email = '$this->email'";
        }

        $result = $this->runDML($query);

        return $result;
    }

    public function delete()
    {

    }
}