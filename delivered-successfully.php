<?php

session_start();

include_once __DIR__."\app\models\Order.php";

$order = new Order();

$order->setDelivered_at(date("y-m-d h:i:s"));
$order->setId($_SESSION["order_id"]);

$isDelivered = $order->orderDelivered();

if($isDelivered)
{
    unset($_SESSION["order_id"]);
    unset($_SESSION["ordered_meals"]);
    unset($_SESSION["quantity_per_ordered_meals"]);
    header("location:order.php");die;
}
else
{
    echo "<div style='color: red'>Sorry something went wrong<br>please try again later</div>";die;
}