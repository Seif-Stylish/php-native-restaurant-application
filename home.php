<?php

    ob_start();

    session_start();

    include_once __DIR__."\app\models\Meal.php";

    if(!isset($_SESSION["user"]))
    {
        header("location:index.php");die;
    }

    $title = "home";

    $meal = new Meal();

    // get most liked meals

    $mostLikedMeals = $meal->getMostLikedMeals();
    $mostLikedMealsArray = [];

    if($mostLikedMeals->num_rows > 0)
    {
        $mostLikedMealsArray = $mostLikedMeals->fetch_all(MYSQLI_ASSOC);
    }

    // get highly rated meals

    $highlyRatedMeals = $meal->getHighlyRatedMeals();
    $highlyRatedMealsArray = [];

    if($highlyRatedMeals->num_rows > 0)
    {
        $highlyRatedMealsArray = $highlyRatedMeals->fetch_all(MYSQLI_ASSOC);
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
        .mainSliderSection
        {
            height: 100vh;
            background-size: cover;
            background-position: center;
            z-index: 1;
        }
        .mainSliderSection .grayLayer
        {
            top: 0;
            background-color: rgba(0 , 0 , 0 , 0.5);
            z-index: -1;
        }
        .mainSliderSection .mainSliderSectionIcons
        {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        .mainSectionIcon
        {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }
        .section1h1
        {
            font-size: 35px;
            line-height: 1.1;
            font-weight: 400;
        }
        .section1h2
        {
            font-size: 67px;
            font-weight: 600;
        }
        .sectionParCosmix
        {
            font-size: 17px;
            font-weight: normal;
            line-height: 27px;
        }
        .mainSliderSection .sliderIcon
        {
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            cursor: pointer;
            background: #007bff;
            color: white;
            transition: 0.3s;
            border-radius: 3px;
        }
        .mainSliderSection .nextSlider
        {
            right: 0;
        }
        .mainSliderSection .previousSlider
        {
            left: 0;
        }
        .mealImage
        {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            border: 6px solid rgba(255, 255, 255, 0.15);
        }
        .mostLikedMeals
        {
            background-image: url("images/main image.jpg");
            background-size: cover;
            background-position: center;
            padding: 100px;
            z-index: 2;
        }
        .mostLikedMeals .likesGrayLayer
        {
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(0 , 0 , 0 , 0.6);
            z-index: -1;
        }
        .sliderButtonDiv
        {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }
        .sectionDescriptionTextH2
        {
            /*text-decoration: underline;*/
            font-size: 3rem;
            color: #1e1e1e;
            font-weight: 700;
        }
        .sectionDescriptionUnderline
        {
            width: 100px;
            height: 5px;
            background-color: #0078ff;
        }
        .imageDiv
        {
            overflow: hidden;
        }
        .imageDiv img
        {
            transition: 0.6s;
        }
        .imageDiv:hover img
        {
            transform: scale(1.3);
        }
        .highlyRatedMeal
        {
            box-shadow: 0 0 29px 0 rgb(68 88 144 / 12%);
        }
        .highlyRatedMealName
        {
            font-size: 1.5rem;
            color: #1e1e1e;
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }
    </style>

    <body>

    <?php
      include __DIR__."\layouts/nav.php";
    ?>

    <section class="mainSliderSection d-flex align-items-center position-relative">

        <div class="w-75 m-auto text-white">
            <h1 class="section1h1">WE ARE BRASS TACKS</h1>
            <h2 class="section1h2">Combo Basket</h2>
            <p class="sectionParCosmix"></p>
        </div>

        <div class="grayLayer position-absolute w-100 h-100"></div>

        <i class="fas fa-arrow-right nextSlider position-absolute p-2 sliderIcon"></i>
        <i class="fas fa-arrow-left previousSlider position-absolute p-2 sliderIcon"></i>

        <div class="mainSliderSectionIcons w-100 d-flex justify-content-center p-5 position-absolute">
            <div class="mainSectionIcon mx-2"></div>
            <div class="mainSectionIcon mx-2"></div>
            <div class="mainSectionIcon mx-2"></div>
        </div>

    </section>

    <div class="py-5 my-4"></div>

    <!-- Most Liked Meals -->

    <h2 class="text-center sectionDescriptionTextH2">Most Liked Meals</h2>
    <div class="sectionDescriptionUnderline m-auto"></div>
    <div class="my-5"></div>
    <section class="mostLikedMeals position-relative">
        <div>
            <div class="likesGrayLayer position-absolute"></div>

            <?php
            
                if(!empty($mostLikedMealsArray))
                {
                    for($i = 0; $i < 3; $i++)
                    {
            ?>

            <div class="container selectedMeal text-center text-white">
                <a href="meal-details.php?mealId=<?php echo $mostLikedMealsArray[$i]["meal_id"]; ?>"><img src="images/<?php echo $mostLikedMealsArray[$i]["category_name"]; ?>/<?php echo $mostLikedMealsArray[$i]["image"]; ?>" class="img-fluid mealImage"></a>
                <h2><?php echo $mostLikedMealsArray[$i]["meal_name"]; ?></h2>
                <p class="my-4"><?php echo $mostLikedMealsArray[$i]["desc_en"]; ?></p>
            </div>

            <?php }} ?>
        
            <div class="p-4 w-100 d-flex justify-content-center">
                <?php for($i = 0; $i < 3; $i++){ ?>
                    <div class="sliderButtonDiv mx-2"></div>
                <?php } ?>
            </div>

        </div>
    </section>

    <div class="py-5 my-4"></div>

    <!-- Highly Rated Meals -->

    <section class="highlyRatedMeals">
        <h2 class="text-center sectionDescriptionTextH2">Highly Rated Meals</h2>
        <div class="sectionDescriptionUnderline m-auto"></div>
        <div class="my-5"></div>

        <div class="m-auto p-5" style="width: 80%;">
            <div class="row">

            <?php
                if(count($highlyRatedMealsArray) > 0)
                {
                    for($i = 0; $i < 3; $i++)
                    {
            ?>

                <div class="col-xl-4">
                    <div class="my-2">
                        <div class="imageDiv">
                        <a href="meal-details.php?mealId=<?php echo $highlyRatedMealsArray[$i]["meal_id"]; ?>"><img src="images/<?php echo $highlyRatedMealsArray[$i]["category_name"]; ?>/<?php echo $highlyRatedMealsArray[$i]["image"]; ?>" class="img-fluid w-100" style="height: 350px;"></a>
                        </div>
                        <div class="bg-white p-3">
                            <h2 class="highlyRatedMealName"><?php echo $highlyRatedMealsArray[$i]["meal_name"]; ?></h2>
                        </div>
                    </div>
                </div>

            <?php }} ?>

            </div>
        </div>

    </section>

    <div class="py-5 my-5"></div>

    <?php
        include __DIR__."\layouts\bootstrapJsFiles.php";
    ?>

    <!-- JavaScript -->

    <script>
        
        var imageSlider = [
            "url('images/breakfast/Combo Basket.jpg')",
            "url('images/breakfast/Grilled Chicken Salad.jpg')",
            "url('images/breakfast/Crispy Shrimp Basket.jpg')"
        ];

        var mealNameSlides = [
            "Combo Basket",
            "Grilled Chicken Salad",
            "Crispy Shrimp Basket"
        ];

        var mealDescription = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.<br> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        ];

        $(".mainSliderSection").css("background-image" , imageSlider[0]);
        document.querySelector(".section1h2").innerHTML = mealNameSlides[0];
        document.querySelector(".sectionParCosmix").innerHTML = mealDescription[0];

        var nextSlider = document.querySelector(".nextSlider");
        var previousSlider = document.querySelector(".previousSlider");


        var mainSectionIcon = Array.from(document.querySelectorAll(".mainSectionIcon"));

        for(var i = 0; i < mainSectionIcon.length; i++)
        {
            mainSectionIcon[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
        }

        mainSectionIcon[0].style.backgroundColor = "#106eea";

        var globalIndex = 0;

        for(var i = 0; i < mainSectionIcon.length; i++)
        {
            mainSectionIcon[i].addEventListener("click" , getSectionImageIndex);
        }

        function getSectionImageIndex()
        {
            var index = mainSectionIcon.indexOf(this);

            globalIndex = index;

            if(mainSectionIcon[index].style.backgroundColor == "rgb(16, 110, 234)")
            {
                return;
            }

            $(".mainSliderSection").fadeOut(0);

            $(".mainSliderSection").css("background-image" , imageSlider[globalIndex]);
            document.querySelector(".section1h2").innerHTML = mealNameSlides[globalIndex];
            document.querySelector(".sectionParCosmix").innerHTML = mealDescription[globalIndex];

            for(var i = 0; i < mainSectionIcon.length; i++)
            {
                mainSectionIcon[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
            }

            mainSectionIcon[index].style.backgroundColor = "#106eea";

            $(".mainSliderSection").fadeIn(300);
           
        }

        nextSlider.addEventListener("click" , getNextImageSlide);
        previousSlider.addEventListener("click" , getPreviousImageSlide);

        function getNextImageSlide()
        {
            globalIndex++;
            if(globalIndex == imageSlider.length)
            {
                globalIndex = 0;
            }
            $(".mainSliderSection").fadeOut(0);
            $(".mainSliderSection").css("background-image" , imageSlider[globalIndex]);
            document.querySelector(".section1h2").innerHTML = mealNameSlides[globalIndex];
            document.querySelector(".sectionParCosmix").innerHTML = mealDescription[globalIndex];

            for(var i = 0; i < mainSectionIcon.length; i++)
            {
                mainSectionIcon[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
            }

            mainSectionIcon[globalIndex].style.backgroundColor = "#106eea";

            $(".mainSliderSection").fadeIn(300);
        }

        function getPreviousImageSlide()
        {
            globalIndex--;
            if(globalIndex < 0)
            {
                globalIndex = imageSlider.length - 1;
            }
            $(".mainSliderSection").fadeOut(0);
            $(".mainSliderSection").css("background-image" , imageSlider[globalIndex]);
            document.querySelector(".section1h2").innerHTML = mealNameSlides[globalIndex];
            document.querySelector(".sectionParCosmix").innerHTML = mealDescription[globalIndex];

            for(var i = 0; i < mainSectionIcon.length; i++)
            {
                mainSectionIcon[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
            }

            mainSectionIcon[globalIndex].style.backgroundColor = "#106eea";

            $(".mainSliderSection").fadeIn(300);
        }

        var selectedMeal = Array.from(document.querySelectorAll(".selectedMeal"));
        var sliderButtonDiv = Array.from(document.querySelectorAll(".sliderButtonDiv"));

        for(var i = 0; i < selectedMeal.length; i++)
        {
            $(selectedMeal[i]).fadeOut(0);
        }
        $(selectedMeal[0]).fadeIn(0);

        for(var i = 0; i < sliderButtonDiv.length; i++)
        {
            sliderButtonDiv[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
        }
        
        sliderButtonDiv[0].style.backgroundColor = "#106eea";



        for(var i = 0; i < sliderButtonDiv.length; i++)
        {
            sliderButtonDiv[i].addEventListener("click" , getNextSelectedImage);
        }

        function getNextSelectedImage()
        {
            var index = sliderButtonDiv.indexOf(this);
            
            for(var i = 0; i < sliderButtonDiv.length; i++)
            {
                if(sliderButtonDiv[i].style.backgroundColor != "rgba(255, 255, 255, 0.5)")
                {
                    sliderButtonDiv[i].style.backgroundColor = "rgba(255, 255, 255, 0.5)";
                }
                if(selectedMeal[i].style.display != "none" && i != index)
                {
                    $(selectedMeal[i]).fadeOut(0);
                }
            }

            sliderButtonDiv[index].style.backgroundColor = "#106eea";
            $(selectedMeal[index]).fadeIn(300);
            
        }

    </script>

    </body>

</html>

<?php
ob_end_flush();
?>