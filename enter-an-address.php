<?php

ob_start();

session_start();

include_once __DIR__."\app\models\Address.php";

$title = "enter an address";

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

$input_is_required = "";
$you_already_have_this_address = "";


if($_POST)
{
    // form is submitted


    // input is required

    if(empty($_POST["address"]))
    {
        $input_is_required = "Pleaee enter an address";
    }
    else
    {
        $address = new Address();

        $address->setUser_id($_SESSION["user"]->id);
        $address->setAddress($_POST["address"]);

        $addressExists = $address->addressExists();

        // address already exists

        if($addressExists->num_rows > 0)
        {
            $you_already_have_this_address = "You already have this address. Please enter a different one";
        }
        else
        {
            $isInserted = $address->create();

            if($isInserted)
            {
                header("location:choose-an-address.php");die;
            }
            else
            {
                echo "<div class='text-danger'>Sorry something went wrong!</div>";die;
            }
        }
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
                <h2 class="text-center text-primary register_h2">Enter An Address</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">
                    
                    <input type="text" class="form-control" placeholder="address" name="address">
                    
                    <div class="py-3"></div>

                    <?php if(!empty($input_is_required)){echo "<div class='text-danger'>$input_is_required</div>";} ?>

                    <?php if(!empty($you_already_have_this_address)){echo "<div class='text-danger'>$you_already_have_this_address</div>";} ?>

                    <div class="py-3"></div>
                    
                    <button class="btn btn-primary">submit</button>
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