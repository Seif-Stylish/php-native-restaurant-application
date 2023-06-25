<?php

ob_start();

session_start();

include_once __DIR__."\app\models\Rate.php";
include_once __DIR__."\app\models\Meal.php";

$title = "rate";

if($_GET)
{
    if(!isset($_GET["mealId"]) || empty($_GET["mealId"]))
    {
        header("location:home.php");
    }
}
else
{
    header("location:home.php");
}

$meal = new Meal();
$meal->setId($_GET["mealId"]);

$mealDetails = $meal->getMealDetails();
$mealDetailsObject = $mealDetails->fetch_object();

$rate = new Rate();

$rate->setUser_id($_SESSION["user"]->id);
$rate->setMeal_id($_GET["mealId"]);

$isRatedPreviously = $rate->read();

if($isRatedPreviously->num_rows)
{
    header("location:meals.php?subcategoryId=".$mealDetailsObject->subcategory_id);
}

$rateValidation = "";

if($_POST)
{
    if($_POST["rate_value"] == 1 ||
    $_POST["rate_value"] == 2 ||
    $_POST["rate_value"] == 3 ||
    $_POST["rate_value"] == 4 ||
    $_POST["rate_value"] == 5)
    {
        $rate->setValue($_POST["rate_value"]);

        $isInserted = $rate->create();

        if($isInserted)
        {
            header("location:meals.php?subcategoryId=".$mealDetailsObject->subcategory_id);
        }
        else
        {
            echo "<div class='text-danger'>sorry something went wrong</div>";die;
        }

    }
    else
    {
        $rateValidation = "<div class='text-danger'>please enter a value from one to 5</div>";
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
                <h2 class="text-center text-primary register_h2">Rate from 1 to 5</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">

                    <select name="rate_value" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <div class="py-3"></div>
                    <?php if(!empty($rateValidation)){echo $rateValidation."<div class='py-3'></div>";} ?>
                    
                    <button class="btn btn-primary px-3">rate</button>
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