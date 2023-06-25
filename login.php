<?php

    include_once __DIR__ ."\app/requests/RegisterRequest.php";

    ob_start();

    session_start();

    if(isset($_SESSION["user"]))
    {
        header("location:home.php");die;
    }

    $title = "login";

    $allInputsAreRequired = "";
    $emailIsInvalid = "";
    $passwordIsInvalid = "";
    $wrongEmailOrPassword = "";

    if($_POST)
    {
        $emailRegex = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
        $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
        $validation = new Validation();

        if(empty($_POST["email"]) || empty($_POST["password"]))
        {
            $allInputsAreRequired = "all inputs are required";
        }
        else
        {
            if(!$validation->regex($_POST["email"] , $emailRegex))
            {
                $emailIsInvalid = "please enter a valid email";
            }

            if(!$validation->regex($_POST["password"] , $passwordRegex))
            {
                $passwordIsInvalid = "minimum eight and maximum 15 characters, at least one uppercase letter, one lowercase letter, one number and one special character";
            }
            
        }

        if($allInputsAreRequired == "" && $emailIsInvalid == "" && $passwordIsInvalid == "")
        {
            if($validation->userExists($_POST["email"])->num_rows > 0)
            {
                $userObject = $validation->userExists($_POST["email"])->fetch_object();

                if($userObject->password == sha1($_POST["password"]))
                {
                    // logged in successfully

                    if(isset($_POST["remember_me"]))
                    {
                        setcookie("remember_me" , $_POST["email"] , time() + (24 * 60 * 60) * 30 * 12 , "/");
                    }

                    $_SESSION["user"] = $userObject;
                    header("location:home.php");die;
                }

                else
                {
                    $wrongEmailOrPassword = "wrong email or password";
                }
            }
            else
            {
                $wrongEmailOrPassword = "wrong email or password";
            }
        }
        
    }

?>

<html>
    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>
    <style>
        body
        {
            background-color: #f5f5f5;
        }
    </style>
    <body>

    <?php
      include __DIR__."\layouts/nav.php";
    ?>


        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="m-auto registerationDiv">
                <h2 class="text-center text-primary register_h2">Login</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5 w-50 m-auto" method="post">
                    
                    <input type="email" class="form-control" placeholder="email" name="email" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>">
                    <div class="py-3"></div>
                    <input type="password" class="form-control" placeholder="password" name="password">
                    <div class="py-3"></div>
                    <span class="text-danger"><?php if(!empty($passwordIsInvalid)){echo $passwordIsInvalid;echo "<div class='py-3'></div>";} ?></span>
                    <div class="text-center text-danger"><?php if(!empty($allInputsAreRequired)){echo $allInputsAreRequired;} ?></div>
                    <div class="text-center text-danger"><?php if(!empty($wrongEmailOrPassword)){echo $wrongEmailOrPassword;} ?></div>
                    <div class="d-flex" style="justify-content: space-between">
                        <div><input type="checkbox" name="remember_me"><span class="mx-1">Remember Me</span></div>
                        <div><button class="btn btn-primary">login</button></div>
                    </div>
                </form>
            </div>
        </div>
        
        

        <?php
            include __DIR__."\layouts\bootstrapJsFiles.php";
        ?>
    </body>
</html>

<?php
ob_end_flush();
?>