<?php

ob_start();

session_start();

include_once __DIR__."\app\models\Meal.php";
include_once __DIR__."\app\models\Category.php";
include_once __DIR__."\app\models\Subcategory.php";

$title = "Brass Tacks";

$meal = new Meal();
$category = new Category();
$subcategory = new Subcategory();

$allCategories = $category->read();
$allCategoriesArray = [];

if($allCategories->num_rows > 0)
{
    $allCategoriesArray = $allCategories->fetch_all(MYSQLI_ASSOC);
}

$allSubCategories = $subcategory->read();
$allSubCategoriesArray = [];

if($allSubCategories->num_rows > 0)
{
    $allSubCategoriesArray = $allSubCategories->fetch_all(MYSQLI_ASSOC);
}

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
        .categoriesList
        {
            list-style-type: none;
        }
        .categoriesList li
        {
            display: inline;
            cursor: pointer;
            text-transform: capitalize;
            font-size: 24px;
            font-weight: 600;
            line-height: 1;
            transition: 0.3s;
        }
        .subcategoriesList
        {
            list-style-type: none;
        }
        .subcategoriesList li
        {
            display: inline;
            cursor: pointer;
            text-transform: capitalize;
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            transition: 0.3s;
        }
        .mealDetailsDivRelative
        {
            overflow: hidden;
        }
        .mealDetailsDivRelative .mealDetailsDivAbsolute
        {
            bottom: -15%;
            width: 90%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            background-color: rgba(255 , 255 , 255 , 0.9);
            justify-content: space-between;
            transition: 0.5s;        
        }
        .mealDetailsDivRelative:hover .mealDetailsDivAbsolute
        {
            bottom: 5%;
            opacity: 1;
        }
        .mealDetailsDivAbsolute i
        {
            cursor: pointer;
            font-size: 1.5rem;
            line-height: 1.5;
            color: #212529;
        }
        .mealNameH4
        {
            font-size: 1.3rem;
            font-weight: 500;
            line-height: 1.2;
            color: #212529;
        }
        .sliderDiv
        {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;

            display: none;
            justify-content: center;
            align-items: center;
        }
        .sliderDiv .sliderGrayLayer
        {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: rgba(0 , 0 , 0 , 0.7);

            z-index: -1;
        }
        .sliderDiv .nextAndPreviousIcons
        {
            top: 50%;
            transform: translateY(-50%);
        }
        .sliderDiv .nextSlieIcon
        {
            right: 15;
            font-size: 25px;
            cursor: pointer;
        }
        .sliderDiv .previousSlieIcon
        {
            left: 15;
            font-size: 25px;
            cursor: pointer;
        }
    </style>

    <body>

        <?php
            include_once __DIR__."\layouts/nav.php";
        ?>

        <div class="p-5 m-4"></div>

        <section>
            <div class="text-center">
                <span class="mealsSpan">Meals</span>
                <h3 class="checkOurMealsH3 my-3">Check Our <span style="color: #106eea;">Meals</span></h3>

                <ul class="categoriesList container py-3">

                    <?php
                        if(count($allCategoriesArray) > 0)
                        {
                            for($i = 0; $i < count($allCategoriesArray); $i++)
                            {
                                $category->setId($allCategoriesArray[$i]["id"]);
                                
                                // get number of subcategories per category

                                $noOfSubcategoriesPerCategory = $category->getNumberOfSubCategoriesPerCategory();
                                $noOfSubcategoriesPerCategoryObject = $noOfSubcategoriesPerCategory->fetch_object();

                                $noOfMealsPerCategory = $category->getNumberOfMealsPerCategory();
                                $noOfMealsPerCategoryObject = $noOfMealsPerCategory->fetch_object();

                                
                    ?>

                    <?php if((isset($noOfSubcategoriesPerCategoryObject->no_of_subcategories) && $noOfSubcategoriesPerCategoryObject->no_of_subcategories > 0)
                    &&(isset($noOfMealsPerCategoryObject->no_of_meals_per_category) && $noOfMealsPerCategoryObject->no_of_meals_per_category > 0)){ ?>

                    <li class="mx-3"><?php echo $allCategoriesArray[$i]["name_en"]; ?></li>

                    <?php } ?>

                    <?php }} ?>
                </ul>

                <div class="py-3"></div>

                        <?php
                            for($i = 0; $i < count($allCategoriesArray); $i++)
                            {
                        ?>

                        <ul class='subcategoriesList'>
                        

                        <?php 
                            $subcategory->setCategory_id($allCategoriesArray[$i]["id"]);

                            $subcategoryList = $subcategory->getSubCategoryByCategory();
                            $subcategoryListArray = [];

                            if($subcategoryList->num_rows > 0)
                            {
                                $subcategoryListArray = $subcategoryList->fetch_all(MYSQLI_ASSOC);
                            }

                            for($x = 0; $x < count($subcategoryListArray); $x++)
                            {
                                $subcategory->setId($subcategoryListArray[$x]["id"]);


                                // get number of meals per subcategory

                                $noOfMealsPerSubcategory = $subcategory->getNumberOfMealsPerSubcategory();
                                $noOfMealsPerSubcategoryObject = $noOfMealsPerSubcategory->fetch_object();

                        ?>

                        <?php if(isset($noOfMealsPerSubcategoryObject->no_of_meals_per_subcategory) && $noOfMealsPerSubcategoryObject->no_of_meals_per_subcategory > 0){ ?>

                        <li class='mx-3'><?php echo $subcategoryListArray[$x]['name_en']; ?></li>

                        <?php } ?>

                        <?php } ?>

                        </ul>

                        <?php } ?>

                <div class="py-3"></div>

            </div>

            <?php
                if(count($allSubCategoriesArray) > 0)
                {
                    for($i = 0; $i < count($allSubCategoriesArray); $i++)
                    {
            ?>

            <div class="container allMeals">

                <div class="row">

                <?php
                    $meal->setSubcategory_id($allSubCategoriesArray[$i]["id"]);

                    $allMeals = $meal->getAllMealsWithFullDetails();
                    $allMealsArray = [];
                    
                    if($allMeals->num_rows > 0)
                    {
                        $allMealsArray = $allMeals->fetch_all(MYSQLI_ASSOC);
                    }

                    for($x = 0; $x < count($allMealsArray); $x++)
                    {
                ?>

                <!-- our meals -->

                <div class="col-xl-4 selectedMealDivDetails">
                    <div class="my-3 mealDetailsDivRelative position-relative">
                        <a href="http://localhost/nti/revision/meal-details.php?mealId=<?php echo $allMealsArray[$x]["meal_id"]; ?>"><img src="images/<?php echo $allMealsArray[$x]["category_name"] ; ?>/<?php echo $allMealsArray[$x]["image"] ; ?>" class="img-fluid w-100" style="height: 350px;"></a>
                        <div class="position-absolute d-flex mealDetailsDivAbsolute p-2">
                            <h4 class="mealNameH4"><?php echo $allMealsArray[$x]["meal_name"]; ?></h4>
                            <i class="fas fa-plus sliderIcon"></i>
                        </div>
                    </div>
                </div>

                <?php } ?>

                </div>

            </div>

            <?php }} ?>
            
        </section>

        <div class="py-5"></div>

        <div class="sliderDiv">

            <img class="img-fluid w-50 h-75">

            <div class="sliderGrayLayer"></div>

            <i class="fas fa-arrow-right text-white nextAndPreviousIcons nextSlieIcon position-absolute"></i>

            <i class="fas fa-arrow-left text-white nextAndPreviousIcons previousSlieIcon position-absolute"></i>

        </div>

        <?php
            include_once __DIR__."\layouts\bootstrapJsFiles.php";
        ?>
        <script>

            var categoriesList = Array.from(document.querySelectorAll(".categoriesList li"));
            var subcategoriesList = Array.from(document.querySelectorAll(".subcategoriesList"));
            var subcategoriesListChild = Array.from(document.querySelectorAll(".subcategoriesList li"));
            var allMeals = Array.from(document.querySelectorAll(".allMeals"));

            for(var i = 0; i < allMeals.length; i++)
            {
                $(allMeals[i]).fadeOut(0);
            }

            $(allMeals[0]).fadeIn(0);

            for(var i = 0; i < categoriesList.length; i++)
            {
                subcategoriesList[i].children[0].style.color = "rgb(16, 110, 234)";
            }



            for(var i = 0; i < subcategoriesList.length; i++)
            {
                $(subcategoriesList[i]).fadeOut(0);
                subcategoriesList[i].style.color = "#444444";
            }

            $(subcategoriesList[0]).fadeIn(0);

            categoriesList[0].style.color = "rgb(16, 110, 234)";

            for(var i = 0; i < categoriesList.length; i++)
            {
                categoriesList[i].addEventListener("click" , switchCategory);
            }

            function switchCategory()
            {
                var index = categoriesList.indexOf(this);

                /*
                if(categoriesList[index].style.color != "rgb(16, 110, 234)")
                {
                    // not selected previously

                    for(var i = 0; i < allMeals.length; i++)
                    {
                        if(allMeals[i].style.display != "none")
                        {
                            allMeals[i].style.display = "none";
                        }
                    }
                }
                */


                for(var i = 0; i < categoriesList.length; i++)
                {
                    if(categoriesList[i].style.color != "#444444")
                    {
                        categoriesList[i].style.color = "#444444";
                    }
                    if(subcategoriesList[i].style.display != "none" && i != index)
                    {
                        subcategoriesList[i].style.display = "none";
                    }
                }
                

                categoriesList[index].style.color = "rgb(16, 110, 234)";
                $(subcategoriesList[index]).fadeIn(300);


                subcategoriesList[index].children[0].style.color = "rgb(16, 110, 234)";


                for(var i = 0; i < subcategoriesListChild.length; i++)
                {
                    if(subcategoriesListChild[i].style.color != "#444444")
                    {
                        subcategoriesListChild[i].style.color = "#444444";
                    }
                }

                
                var subcategoryIndex = subcategoriesListChild.indexOf(subcategoriesList[index].children[0]);
                
                for(var i = 0; i < allMeals.length; i++)
                {
                    if(allMeals[i].style.display != "none" && i != subcategoryIndex)
                    {
                        allMeals[i].style.display = "none";
                    }
                }

                subcategoriesListChild[subcategoryIndex].style.color = "rgb(16, 110, 234)";
                $(allMeals[subcategoryIndex]).fadeIn(300);

            }

            for(var i = 0; i < subcategoriesListChild.length; i++)
            {
                subcategoriesListChild[i].addEventListener("click" , switchSubCategory);
            }

            function switchSubCategory()
            {
                var index = subcategoriesListChild.indexOf(this);

                for(var i = 0; i < subcategoriesListChild.length; i++)
                {
                    if(subcategoriesListChild[i].style.color != "#444444")
                    {
                        subcategoriesListChild[i].style.color = "#444444";
                    }
                    if(allMeals[i].style.display != "none" && i != index)
                    {
                        allMeals[i].style.display = "none";
                    }
                }

                subcategoriesListChild[index].style.color = "rgb(16, 110, 234)";
                $(allMeals[index]).fadeIn(300);
            }


            // images slider

            var allImagesSlider = Array.from(document.querySelectorAll(".mealDetailsDivRelative img"));
            var sliderIcon = Array.from(document.querySelectorAll(".sliderIcon"));

            nextSlieIcon = document.querySelector(".nextSlieIcon");
            previousSlieIcon = document.querySelector(".previousSlieIcon");

            globalSliderIndex = 0;

            for(var i = 0; i < sliderIcon.length; i++)
            {
                sliderIcon[i].addEventListener("click" , showImageSlider);
            }

            $(".sliderGrayLayer").click(hideImageSlider);

            function showImageSlider()
            {
                var index = sliderIcon.indexOf(this);

                $(".sliderDiv img").attr("src" , $(allImagesSlider[index]).attr("src"));
                $(".sliderDiv").fadeIn(300);
                $(".sliderDiv").css("display" , "flex");

                $("html , body").css("overflow" , "hidden");

                globalSliderIndex = index;
            }

            function hideImageSlider()
            {
                $(".sliderDiv").fadeOut(300);
                $("html , body").css("overflow" , "auto");
            }

            
            nextSlieIcon.addEventListener("click" , getNextImage);
            previousSlieIcon.addEventListener("click" , getPreviousImage);

            function getNextImage()
            {
                globalSliderIndex++;
                if(globalSliderIndex == allImagesSlider.length)
                {
                    globalSliderIndex = 0;
                }

                $(".sliderDiv img").fadeOut(0);
                $(".sliderDiv img").attr("src" , $(allImagesSlider[globalSliderIndex]).attr("src"));
                $(".sliderDiv img").fadeIn(300);

            }

            function getPreviousImage()
            {
                globalSliderIndex--;
                if(globalSliderIndex < 0)
                {
                    globalSliderIndex = allImagesSlider.length - 1;
                }

                $(".sliderDiv img").fadeOut(0);
                $(".sliderDiv img").attr("src" , $(allImagesSlider[globalSliderIndex]).attr("src"));
                $(".sliderDiv img").fadeIn(300);
            }

        </script>
    </body>

</html>

<?php
ob_end_flush();
?>