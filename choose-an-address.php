<?php

ob_start();

session_start();

include_once __DIR__."\app\models\Address.php";
include_once __DIR__."\app\models\Order.php";
include_once __DIR__."\app\models\Meal_order.php";
include_once __DIR__."\app\models\Meal.php";

//print_r($_SESSION);die;


$address = new Address();
$order = new Order();
$meal = new Meal();
$meal_order = new Meal_order();


$title = "choose an address";

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

$user_addresses = "";
$user_addresses_array = [];

$address->setUser_id($_SESSION["user"]->id);

$user_addresses = $address->getUserAddresses();

if($user_addresses->num_rows <= 0 || empty($_SESSION["ordered_meals"]) || empty($_SESSION["quantity_per_ordered_meals"]))
{
    header("location:order.php");die;
}

$user_addresses_array = $user_addresses->fetch_all(MYSQLI_ASSOC);

$please_choose_a_valid_address = "";
$please_enter_a_payment_method = "";

if($_POST)
{
    //print_r($_POST);die;

    $address_value = $_POST["address"];
    $payment_method_value = $_POST["payment_method"];

    $address->setUser_id($_SESSION["user"]->id);
    $address->setAddress($address_value);

    $address_exists = $address->addressExists();

    if($address_exists->num_rows <= 0)
    {
       $please_choose_a_valid_address = "please choose a valid address";
    }

    if(empty($payment_method_value))
    {
        $please_enter_a_payment_method = "please enter a payment method";
    }

    if(empty($please_choose_a_valid_address) && empty($please_enter_a_payment_method))
    {
        // everything went fine

        $address_exists_object = $address_exists->fetch_object();

        $address_id = $address_exists_object->id;

        $order->setPayment_method($payment_method_value);
        $order->setAddress_id($address_id);

        $isCreated = $order->create();

        if($isCreated)
        {
            // order created successfully

            $all_orders = $order->read();

            $all_orders_array = $all_orders->fetch_all(MYSQLI_ASSOC);

            $this_order = $all_orders_array[count($all_orders_array) - 1];

            $ordered_meals = $_SESSION["ordered_meals"];
            $quantity_per_ordered_meals = $_SESSION["quantity_per_ordered_meals"];

            $something_went_wrong_in_the_order = 0;

            for($i = 0; $i < count($ordered_meals); $i++)
            {
                // equation for the price (quantity x meal_price)

                $meal->setId($ordered_meals[$i]);

                $single_meal = $meal->read();

                $single_meal_object = $single_meal->fetch_object();

                $price = $quantity_per_ordered_meals[$i] * $single_meal_object->price;

                $meal_order->setMeal_id($ordered_meals[$i]);
                $meal_order->setOrder_id($this_order["id"]);
                $meal_order->setQuantity($quantity_per_ordered_meals[$i]);
                $meal_order->setPrice($price);

                $is_created_meal_order = $meal_order->create();

                if(!$is_created_meal_order)
                {
                    $something_went_wrong_in_the_order = 1;
                    break;
                }
            }

            if($something_went_wrong_in_the_order == 1)
            {
                echo "<div style='color: red;'>Sorry something went wrong!<br>please try again later</div>";die;
            }
            else
            {
                $_SESSION["order_id"] = $this_order["id"];
                
                header("location:ordered-successfully.php");die;
                
            }
        }
        else
        {
            echo "<div style='color: red;'>Sorry something went wrong!<br>please try again later</div>";die;
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
                <h2 class="text-center text-primary register_h2">Order Now</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">

                    <select class="form-control" name="address">
                        <?php for($i = 0; $i < count($user_addresses_array); $i++){ ?>
                        <option value="<?php echo $user_addresses_array[$i]["address"] ?>" <?php if(isset($_POST["address"]) && $_POST["address"] == $user_addresses_array[$i]["address"]){echo "selected";} ?>><?php echo $user_addresses_array[$i]["address"]; ?></option>
                        <?php } ?>
                    </select>

                    <?php if(!empty($please_choose_a_valid_address)){echo "<div class='text-danger py-3'>$please_choose_a_valid_address</div>";} ?>

                    <div class="py-3"></div>
                    
                    <input type="text" class="form-control" placeholder="payment method" name="payment_method">

                    <?php if(!empty($please_enter_a_payment_method)){echo "<div class='text-danger py-3'>$please_enter_a_payment_method</div>";} ?>
                    
                    <div class="py-3"></div>

                    <div class="py-3"></div>
                    
                    <button class="btn btn-primary">order</button>
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