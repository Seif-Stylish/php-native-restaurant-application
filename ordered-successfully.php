<?php

ob_start();

session_start();

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

if(empty($_SESSION["ordered_meals"]) || empty($_SESSION["quantity_per_ordered_meals"]) || !isset($_SESSION["order_id"]))
{
    header("location:order.php");die;
}

include_once __DIR__."\app\models\Meal.php";

include_once __DIR__."\app\models\Order.php";

$order = new Order();

$order->setId($_SESSION["order_id"]);

$singleOrderDetails = $order->getSingleOrder()->fetch_object();

$order_delivered_at = $singleOrderDetails->delivered_at;

if($order_delivered_at)
{
    header("location:order.php");die;
}

$meal = new Meal();

$title = "order details";

$ordered_meals = $_SESSION["ordered_meals"];
$quantity_per_ordered_meals = $_SESSION["quantity_per_ordered_meals"];


?>

<html>
    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>

    <body>

        <?php
            include __DIR__."\layouts/nav.php";
        ?>

        <div class="p-5 m-5"></div>

        <div class="container text-center p-5 bg-warning orderDetails">

            <?php

                $total_price = 0;
                
                for($i = 0; $i < count($ordered_meals); $i++){

                    $meal->setId($ordered_meals[$i]);

                    $singleMealDetails = $meal->read();

                    $singleMealDetailsObject = $singleMealDetails->fetch_object();

                    $total_price = $total_price + $singleMealDetailsObject->price * $quantity_per_ordered_meals[$i];

            ?>

            <strong>meal name: </strong><span><?php echo $singleMealDetailsObject->name_en; ?></span><br>
            <strong>meal price: </strong><span><?php echo $singleMealDetailsObject->price; ?></span><br>
            <strong>meal quantity: </strong><span><?php echo $quantity_per_ordered_meals[$i]; ?></span><br><br>

            <?php } ?>

            <h2>total price: <?php echo $total_price; ?></h2>

            <?php if(!$order_delivered_at){ ?><a href="delivered-successfully.php"><button class="btn btn-primary mt-5 mb-0">delivered</button></a><?php } ?>

        </div>


        <?php
            include __DIR__."\layouts\bootstrapJsFiles.php";
        ?>

    </body>

</html>