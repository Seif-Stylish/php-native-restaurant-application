<?php

ob_start();

session_start();

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

include_once __DIR__."\app\models\Meal.php";
include_once __DIR__."\app\models\Address.php";

$meal = new Meal();
$address = new Address();

$title = "order";

$now = date("H:i:s");//or date("His")

$breakfast_start = '7:00:00';//or '70000'
$breakfast_end = '12:00:00';//or '120000'

$lunch_start = '12:00:01';//or '120001'
$lunch_end = '18:45:00';//or '180000'

$dinner_start = '18:00:01';//or '180001'
$dinner_end = '23:59:99';//or '180000'

//echo $now;die;

$categoryname = "";
$availableMeals = "";
$availableMealsArray = [];

if($now >= $breakfast_start && $now <= $breakfast_end)
{
    //echo "breakfast";die;
    $categoryname = "breakfast";
}
else if($now >= $lunch_start && $now <= $lunch_end)
{
    //echo "lunch";die;
    $categoryname = "lunch";
}
else if($now >= $dinner_start && $now <= $dinner_end)
{
    //echo "dinner";die;
    $categoryname = "dinner";
}
else
{
    $categoryname = "breakfast";
    //echo "restaurant is closed now";die;
}

if(!empty($categoryname))
{
    $availableMeals = $meal->makeAnOrder($categoryname);
}

if($availableMeals->num_rows > 0)
{
    $availableMealsArray = $availableMeals->fetch_all(MYSQLI_ASSOC);
}

$empty_meals = [];
$empty_quantities = [];

$meal_is_selected_but_quantity_is_not_entered = "";
$quantity_is_entered_but_meal_is_not_selected = "";
$invalid_quantity = "";
$out_of_stock = "";

$no_selected_meal = "";


$enterd_quantities_array = [];
$selected_meals_array = [];
$invalid_quantities_array = [];
$out_of_stock_meals = [];

//$ordered_successfully = "";

if($_POST && isset($_POST["meal_button"]))
{
    // form submitted

    if(count(array_keys($_POST)) <= count($availableMealsArray) + 1)
    {
        // no meal selected

        $no_selected_meal = "you must choose atleast one meal";

        //echo "you must choose atleast one meal";die;
    }


    else
    {
        for($x = 0; $x < count($availableMealsArray); $x++)
        {
            if(isset($_POST["meal_id".($x + 1)]))
            {
                // meal is selected

                if(empty($_POST["quantity".($x + 1)]))
                {
                    // meal is selected and quantity is empty

                    //echo "please fill in the quantity";die;

                    $meal_is_selected_but_quantity_is_not_entered = "yes";

                    array_push($empty_quantities , $x);
                }
            }
            else if(!empty($_POST["quantity".($x + 1)]) && !isset($_POST["meal_id".($x + 1)]))
            {
                // quantity is not empty AND meal is not selected

                //echo "please select the meal";die;

                $quantity_is_entered_but_meal_is_not_selected = "yes";

                array_push($empty_meals , $x);
            }
            if(isset($_POST["meal_id".($x + 1)]) && !empty($_POST["quantity".($x + 1)]) &&  (  !(is_numeric($_POST["quantity".($x + 1)])) ||  $_POST["quantity".($x + 1)] <= 0    )   )
            {
                // invalid input

                //echo "please enter a valid quantity";die;

                $invalid_quantity = "yes";

                array_push($invalid_quantities_array , $x);
            }
            else if(is_numeric($_POST["quantity".($x + 1)]) && $_POST["quantity".($x + 1)] > 0)
            {
                if(isset($_POST["meal_id".($x + 1)]))
                {
                    // out of stock

                    $meal->setId($_POST["meal_id".($x + 1)]);

                    $this_meal = $meal->read();

                    $this_meal_object = $this_meal->fetch_object();

                    if($_POST["quantity".($x + 1)] > $this_meal_object->quantity)
                    {
                        $out_of_stock = "yes";

                        array_push($out_of_stock_meals , $x);
                    }
                }
            }
        }
    }

    if(empty($no_selected_meal) &&
    empty($meal_is_selected_but_quantity_is_not_entered) &&
    empty($quantity_is_entered_but_meal_is_not_selected) &&
    empty($invalid_quantity) &&
    empty($out_of_stock))
    {
        // no errors

        for($i = 0; $i < count($availableMealsArray); $i++)
        {
            if(!empty($_POST["quantity".($i + 1)]))
            {
                array_push($enterd_quantities_array , $_POST["quantity".($i + 1)]);
            }

            if(isset($_POST["meal_id".($i + 1)]))
            {
                array_push($selected_meals_array , $_POST["meal_id".($i + 1)]);
            }
        }

        $_SESSION["ordered_meals"] = $selected_meals_array;
        $_SESSION["quantity_per_ordered_meals"] = $enterd_quantities_array;

        $address->setUser_id($_SESSION["user"]->id);
        $user_addresses = $address->read();

        if($user_addresses->num_rows > 0)
        {
            header("location:choose-an-address.php");die;
        }
        else
        {
            header("location:enter-an-address.php");die;
        }

        //header("location:ordered-successfully.php");die;
    }
}

?>

<html>
    
    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>

    <style>
        .checkOurMealsH3
        {
            font-size: 32px;
            font-weight: 700;
            font-family: "Roboto" , "sans-serif";
            text-transform: capitalize;
        }
    </style>

    <body>

        <?php
            include __DIR__."\layouts/nav.php";
        ?>


        <div class="my-5 py-4"></div>

        <h3 class="checkOurMealsH3 text-center my-3">It's <span style="color: #106eea;"><?php if(!empty($categoryname)){echo $categoryname;} ?> Time</span></h3>
        
        <div class="py-3"></div>

        <?php if(!empty($no_selected_meal)){echo "<div class='text-danger text-center pb-3'>$no_selected_meal</div>";} ?>
        <?php
            if(isset($_POST["meal_button"]))
            {
                if(empty($no_selected_meal) &&
                empty($meal_is_selected_but_quantity_is_not_entered) &&
                empty($quantity_is_entered_but_meal_is_not_selected) &&
                empty($invalid_quantity) &&
                empty($out_of_stock))
                {
                    echo "<div class='text-primary text-center pb-3'>ordered successfully</div>";
                }
                else
                {
                    echo "<div class='text-danger text-center pb-3'>please fix the errors</div>";
                }
            }
        ?>


        
            <div class="container">
                <form method="post">
                    <div class="row">

                        <?php if(count($availableMealsArray) > 0){ ?>

                            <?php for($i = 0; $i < count($availableMealsArray); $i++){ ?>

                                

                                <div class="col-xl-4">
                                    <div class="my-2 p-3" style="border: 2px solid #106eea;; border-radius: 7px;">
                                        <input type="checkbox" name="meal_id<?php echo $i + 1; ?>" value="<?php echo $availableMealsArray[$i]["meal_id"]; ?>" class="checkboxInputField" <?php if(isset($_POST["meal_id".($i + 1)])){echo "checked";} ?>>
                                        <div class="my-2"></div>
                                        <img src="images/<?php echo $availableMealsArray[$i]["category_name"]; ?>/<?php echo $availableMealsArray[$i]["image"]; ?>" class="img-fluid w-100" style="height: 350px;">
                                        <div class="my-3"></div>
                                        <input name="quantity<?php echo $i + 1; ?>" type="number" class="form-control mealQuantityInput" placeholder="quantity" value="<?php if(isset($_POST["quantity".($i + 1)]) && !empty($_POST["quantity".($i + 1)])){echo $_POST["quantity".($i + 1)];} ?>">
                                        <?php
                                            for($x = 0;$x < count($empty_meals); $x++){
                                        ?>

                                        <?php if(!empty($empty_meals) && isset($empty_meals[$x]) && $empty_meals[$x] == $i){ ?>
                                            
                                            <div class="text-danger text-center my-3">please select the meal</div>

                                        <?php } ?>

                                        <?php } ?>

                                        <?php
                                            for($x = 0;$x < count($empty_quantities); $x++){
                                        ?>

                                        <?php if(!empty($empty_quantities) && isset($empty_quantities[$x]) && $empty_quantities[$x] == $i){ ?>
                                            
                                            <div class="text-danger text-center my-3">please enter a quantity</div>

                                        <?php } ?>

                                        <?php } ?>




                                        <?php
                                            for($x = 0;$x < count($invalid_quantities_array); $x++){
                                        ?>

                                        <?php if(!empty($invalid_quantities_array) && isset($invalid_quantities_array[$x]) && $invalid_quantities_array[$x] == $i){ ?>
                                            
                                            <div class="text-danger text-center my-3">please enter a valid quantity</div>

                                        <?php } ?>

                                        <?php } ?>




                                        <?php
                                            for($x = 0;$x < count($out_of_stock_meals); $x++){
                                        ?>

                                        <?php if(!empty($out_of_stock_meals) && isset($out_of_stock_meals[$x]) && $out_of_stock_meals[$x] == $i){ ?>
                                            
                                            <div class="text-danger text-center my-3">out of stock</div>

                                        <?php } ?>

                                        <?php } ?>



                                        <div class="my-3">
                                            <h4><?php echo $availableMealsArray[$i]["meal_name"]; ?></h4>
                                        </div>
                                        


                                    </div>
                                </div>

                            <?php } ?>

                        <?php } ?>

                    </div>

                    <button class="btn btn-primary my-3" name="meal_button" value="submitted">submit</button>

                </form>
            </div>

        <?php
            include __DIR__."\layouts\bootstrapJsFiles.php";
        ?>

        <script>

            var checkboxInputField = Array.from(document.querySelectorAll(".checkboxInputField"));
            var mealQuantityInput = Array.from(document.querySelectorAll(".mealQuantityInput"));

            for(var i = 0; i < mealQuantityInput.length; i++)
            {
                mealQuantityInput[i].addEventListener("blur" , quantityFilled);
                mealQuantityInput[i].addEventListener("keyup" , quantityFilled);
            }

            function quantityFilled()
            {
                var index = mealQuantityInput.indexOf(this);

                var quantityValue = mealQuantityInput[index].value;

                if(quantityValue != "" && quantityValue > 0)
                {
                    $(checkboxInputField[index]).attr("checked" , true);
                }
                else
                {
                    $(checkboxInputField[index]).attr("checked" , false);
                }
            }

        </script>

    </body>

</html>

<?php
ob_end_flush();
?>