<?php

include_once __DIR__ ."\app/models/User.php";

$title = "verification code";

if($_GET)
{   
    if(isset($_GET["page"]))
    {
        if(!$_GET["page"] == "confirmCode")
        {
            header("location:index.php");die;
        }
    }
    else
    {
        header("location:index.php");die;
    }
}
else
{
    header("location:index.php");die;
}

session_start();

$user = new User();

$user->setEmail($_SESSION["email_verification_registeration"]);

$singleUser =  $user->getSingleUser();

$singleUserObject = $singleUser->fetch_object();


$codeIsRequired = "";
$incorrectVerificationCode = "";


if($_POST)
{
    if($_POST["verification_code"] == "")
    {
        $codeIsRequired = "please enter the verification code";
    }
    else if($_POST["verification_code"] != $singleUserObject->code)
    {
        $incorrectVerificationCode = "incorrect verification code";
    }
    else
    {
        // verification code is correct
        header("location:login.php");
    }
}

?>

<html>

    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>
    <body>

        <?php
        include __DIR__."\layouts/nav.php";
        ?>

        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="m-auto registerationDiv">
                <h2 class="text-center text-primary register_h2">Register</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">
                    <input type="number" class="form-control" placeholder="enter the verification code" name="verification_code">
                    <div class="py-3"></div>
                    <div class="text-center text-danger"><?php echo $codeIsRequired; ?></div>
                    <div class="text-center text-danger"><?php echo $incorrectVerificationCode; ?></div>
                    <button class="btn btn-primary" value="registered" type="submit">register</button>
                </form>
            </div>
        </div>


        <?php
            include __DIR__."\layouts\bootstrapJsFiles.php";
        ?>
    </body>


</html>