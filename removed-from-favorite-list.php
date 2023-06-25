<?php

session_start();

include_once __DIR__."\app\models\FavoriteList.php";

if($_GET)
{
    if(!isset($_GET["mealId"]) || empty($_GET["mealId"]))
    {
        header("location:home.php");die;
    }
    if(!isset($_GET["subcategoryId"]) || empty($_GET["subcategoryId"]))
    {
        header("location:home.php");die;
    }
}


$favoritelist = new FavoriteList();

$favoritelist->setUser_id($_SESSION["user"]->id);
$favoritelist->setMeal_id($_GET["mealId"]);

$isDeleted = $favoritelist->delete();

if($isDeleted)
{
    header("location:meals.php?subcategoryId=".$_GET["subcategoryId"]);
}
else
{
    echo "<div class='text-danger'>sorry something went wrong</div>";
}