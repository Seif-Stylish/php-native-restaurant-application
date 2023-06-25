<?php

ob_start();

session_start();

if(isset($_SESSION["user"]))
{
    header("location:home.php");die;
}

include_once __DIR__ ."\app/requests/RegisterRequest.php";
include_once __DIR__ ."\app/models/User.php";
include_once __DIR__ ."\app/services/mail.php";

if(isset($_COOKIE["remember_me"]))
{
    $user = new User();
    
    $user->setEmail($_COOKIE["remember_me"]);
    
    $singleUser = $user->getSingleUser();
    
    $singleUserObject = $singleUser->fetch_object();
    
    $_SESSION["user"] = $singleUserObject;

    header("location:home.php");die;
}

$allInputsAreRequired = "";
$emailIsInvalid = "";
$phoneNumberIsInvalid = "";
$passwordIsInvalid = "";
$emailExists = "";
$phoneExists = "";
$passwordConfirmationIsWrong = "";

$somethingWentWrong = "";

if($_POST)
{
    $emailRegex = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
    $phoneRegex = "/^01[0-2,5,9]{1}[0-9]{8}$/";
    $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";

    $validation = new Validation();

    if($validation->isRequired($_POST["first_name"]) == true ||
    $validation->isRequired($_POST["last_name"]) == true ||
    $validation->isRequired($_POST["email"]) == true ||
    $validation->isRequired($_POST["phone"]) == true ||
    $validation->isRequired($_POST["gender"]) == true ||
    $validation->isRequired($_POST["password"]) == true ||
    $validation->isRequired($_POST["confirm_password"]) == true
    )
    {
        $allInputsAreRequired = "all inputs are required";
        $registeredSuccessfully = 0;
    }
    else
    {
        if(!$validation->regex($_POST["email"] , $emailRegex))
        {
            $emailIsInvalid = "please enter a valid email";
        }

        else
        {
            
            if(!$validation->isUnique("users" , "email" , $_POST["email"]))
            {
                $emailExists = "this email already exists";
            }
        }

        if(!$validation->regex($_POST["phone"] , $phoneRegex))
        {
            $phoneNumberIsInvalid = "please enter a valid phone number";
        }

        else
        {
            if(!$validation->isUnique("users" , "phone" , $_POST["phone"]))
            {
                $phoneExists = "this phone number already exists";
            }   
        }

        if(!$validation->regex($_POST["password"] , $passwordRegex))
        {
            $passwordIsInvalid = "minimum eight and maximum 15 characters, at least one uppercase letter, one lowercase letter, one number and one special character";
        }

        else
        {
            if($_POST["password"] != $_POST["confirm_password"])
            {
                $passwordConfirmationIsWrong = "please confirm the password correctly";
            }
        }
        $registeredSuccessfully = 0;
    }

    if(empty($allInputsAreRequired) &&
    empty($emailIsInvalid) &&
    empty($phoneNumberIsInvalid) &&
    empty($passwordIsInvalid) &&
    empty($emailExists) &&
    empty($phoneExists) &&
    empty($passwordConfirmationIsWrong))
    {
        //echo $isInserted;die;

        $user = new User();
        $user->setFirst_name($_POST["first_name"]);
        $user->setLast_name($_POST["last_name"]);
        $user->setEmail($_POST["email"]);
        $user->setPhone($_POST["phone"]);
        $user->setGender($_POST["gender"]);
        $user->setPassword($_POST["password"]);
        $code = $code = rand(10000 , 99999);
        $user->setCode($code);
        $isInserted = $user->create();
        if($isInserted)
        {
            header("location:login.php");die;
            // send mail with code
            // mail to => $_POST["email"]
            // mail from => any mail
            // mail subject => verification code
            // mail code => hello name, your verification code is 12345 thank you.

            /*
            $subject = "verification code";
            $body = "hello {$_POST['first_name']} {$_POST['last_name']}<br> your verification code is {$code}<br><br> thank you.";

            $mailResult = new Mail($_POST["email"] , $subject , $body);

            $mailResult->send();
            */

            /*
            if($mailResult)
            {
                $_SESSION["email_verification_registeration"] = $_POST["email"];
                header("location:check-code.php?page=confirmCode");die;
            }
            else
            {
                $somethingWentWrong = "something went wrong please try again later";
            }
            */


            // header to page (check code)
        }
        else
        {
            $somethingWentWrong = "something went wrong please try again later";
        }
    }
}

$title = "register";

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

    <div class="py-5"></div>

        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="m-auto registerationDiv">
                <h2 class="text-center text-primary register_h2">Register</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">
                    <input type="text" class="form-control" placeholder="first name" name="first_name" value="<?php if(isset($_POST["first_name"])){echo $_POST["first_name"];} ?>">
                    <div class="py-3"></div>
                    <input type="text" class="form-control" placeholder="last name" name="last_name" value="<?php if(isset($_POST["last_name"])){echo $_POST["last_name"];} ?>">
                    <div class="py-3"></div>
                    <input type="email" class="form-control" placeholder="email" name="email" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>">
                    <div class="py-3"></div>
                    <?php echo "<span class='text-danger'>$emailIsInvalid</span>"; if(!empty($emailIsInvalid)){echo "<div class='py-3'></div>";}?>
                    <?php echo "<span class='text-danger'>$emailExists</span>"; if(!empty($emailExists)){echo "<div class='py-3'></div>";}?>
                    <input type="number" class="form-control" placeholder="phone" name="phone" value="<?php if(isset($_POST["phone"])){echo $_POST["phone"];} ?>">
                    <div class="py-3"></div>
                    <?php echo "<span class='text-danger'>$phoneNumberIsInvalid</span>"; if(!empty($phoneNumberIsInvalid)){echo "<div class='py-3'></div>";}?>
                    <?php echo "<span class='text-danger'>$phoneExists</span>"; if(!empty($phoneExists)){echo "<div class='py-3'></div>";}?>
                    <select name="gender" class="form-control">
                        <option value="m" <?php if(isset($_POST["gender"]) && $_POST["gender"] == "m"){echo "selected";} ?>>male</option>
                        <option value="f" <?php if(isset($_POST["gender"]) && $_POST["gender"] == "f"){echo "selected";} ?>>female</option>
                    </select>
                    <div class="py-3"></div>
                    <input type="password" class="form-control" placeholder="password" name="password">
                    <div class="py-3"></div>
                    <?php echo "<span class='text-danger'>$passwordIsInvalid</span>"; if(!empty($passwordIsInvalid)){echo "<div class='py-3'></div>";}?>
                    <input type="password" class="form-control" placeholder="confirm password" name="confirm_password">
                    <div class="py-3"></div>
                    <?php echo "<span class='text-danger'>$passwordConfirmationIsWrong</span>"; if(!empty($passwordConfirmationIsWrong)){echo "<div class='py-3'></div>";}?>
                    <div class="text-center text-danger"><?php echo $allInputsAreRequired; ?></div>
                    <div class="text-center text-danger"><?php if(!empty($somethingWentWrong)){echo $somethingWentWrong;} ?></div>
                    <button class="btn btn-primary" type="submit">register</button>
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