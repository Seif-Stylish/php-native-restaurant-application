<?php

ob_start();

session_start();

include_once __DIR__."\app\models\FavoriteList.php";

$title = "my favorite meals";

$favoritelist = new FavoriteList();

$favoritelist->setUser_id($_SESSION["user"]->id);

$myFavoriteMeals = $favoritelist->getFavoriteMealsForUser();
$myFavoriteMealsArray = [];


if($myFavoriteMeals->num_rows > 0)
{
    $myFavoriteMealsArray = $myFavoriteMeals->fetch_all(MYSQLI_ASSOC);
}

$myArray = [1 , 2 , 3 , 4 , 5];

?>

<html>

    <?php
        include_once __DIR__."\layouts\bootstrapCssFiles.php";
    ?>

    <style>

        .mealsSpan
        {
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: 700;
            padding: 8px 20px;
            background: #e7f1fd;
            color: #106eea;
            text-transform: uppercase;
            border-radius: 50px;
        }
        .checkOurMealsH3
        {
            font-size: 32px;
            font-weight: 700;
            font-family: "Roboto" , "sans-serif";
        }
        .yourFavMeal
        {
            overflow: hidden;
        }
        .yourFavMeal .mealNameDiv
        {
            bottom: -15%;
            background-color: rgba(255 , 255 , 255 , 0.9);
            width: 90%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: 0.5s;   
        }
        .mealNameH4
        {
            font-size: 1.4rem;
            font-weight: 500;
            line-height: 1.2;
            color: #212529;
        }
        .yourFavMeal:hover .mealNameDiv
        {
            bottom: 5%;
            opacity: 1;
        }


    </style>

    <body>

        <?php
            include_once __DIR__."\layouts/nav.php";
        ?>

        <div class="p-5 m-4"></div>

        <section>
            <div class="text-center">

                <span class="mealsSpan">favorite meals</span>
                <h3 class="checkOurMealsH3 my-3">Check My <span style="color: #106eea;">Favorite Meals</span></h3>

                <div class="container">
                    <div class="row">
                    
                        <?php if(!empty($myFavoriteMealsArray)){ ?>

                            <?php for($i = 0; $i < count($myFavoriteMealsArray); $i++){ ?>

                                <div class="col-xl-4">
                                    <div class="my-3 position-relative yourFavMeal">
                                        <div class="position-absolute p-2 mealNameDiv">
                                            <h4 class="mealNameH4"><?php echo $myFavoriteMealsArray[$i]["meal_name"]; ?></h4>
                                        </div>
                                        <a href="meal-details.php?mealId=<?php echo $myFavoriteMealsArray[$i]["id"]; ?>"><img src="images/<?php echo $myFavoriteMealsArray[$i]["category_name"]; ?>/<?php echo $myFavoriteMealsArray[$i]["image"]; ?>" class="img-fluid w-100" style="height: 350px;"></a>
                                    </div>
                                </div>

                            <?php } ?>

                        <?php }else{ ?>

                        <div class="w-100 text-center my-5 text-danger"><h2 class="text-center">You don't have favorite meals yet</h2></div>

                        <?php } ?>

                    </div>
                </div>

            </div>
        </section>

        <?php
            include_once __DIR__."\layouts\bootstrapJsFiles.php";
        ?>
        
        <!--
        <script>
            
            
            var myJsArray = 2;

            console.log(myJsArray);

        </script>
        -->

    </body>

</html>

<?php
ob_end_flush();
?>