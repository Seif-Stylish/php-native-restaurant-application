<?php

    ob_start();

    session_start();

    $title = "meals";

    include_once __DIR__."/app/models/Category.php";
    include_once __DIR__."/app/models/Subcategory.php";
    include_once __DIR__."/app/models/Meal.php";
    include_once __DIR__."\app\models\Like.php";
    include_once __DIR__."\app\models\Comment.php";
    include_once __DIR__."\app\models\Rate.php";
    include_once __DIR__."\app\models\FavoriteList.php";

    $category = new Category();
    $subcategory = new Subcategory();
    $meal = new Meal();
    $like = new Like();
    $comment = new Comment();



    $rate = new Rate();

    $isRated = $rate->read();

    if(!isset($_SESSION["user"]))
    {
        header("location:index.php");die;
    }

    $subcategoryId = "";
    $subcategoryExists = "";
    $subcategoryHasMeals = "";

    if($_GET)
    {
        if(isset($_GET["subcategoryId"]))
        {
            $subcategoryId = $_GET["subcategoryId"];
            $subcategory->setId($subcategoryId);
            $subcategoryExists = $subcategory->getSingleSubcategory();
            $subcategoryHasMeals = $subcategory->getNumberOfMealsPerSubcategory();
        }
    }

    if(!isset($_GET["subcategoryId"])
    || empty($subcategoryId)
    || $_GET["subcategoryId"] <= 0
    || (isset($subcategoryExists->num_rows) && $subcategoryExists->num_rows == 0)
    || (isset($subcategoryHasMeals->num_rows) && $subcategoryHasMeals->num_rows == 0))
    {
        header("location:meals.php?subcategoryId=1");
    }

    $allCategories = $category->read()->fetch_all(MYSQLI_ASSOC);

    $meal->setSubcategory_id($subcategoryId);

    $mealsBySubcategory = $meal->getMealBySubcategory()->fetch_all(MYSQLI_ASSOC);

    $category->setId($_GET["subcategoryId"]);

    $categoryBySubcategory = $category->getCategoryBySubcategory();

    $categoryName = $categoryBySubcategory->fetch_object()->name_en;

?>

<html>

    
    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>

    <style>
        .categoriesList
        {
            list-style-type: none;
        }
        .categoriesList li
        {
            cursor: pointer;
        }
        .categoryName
        {
            font-size: 30px;
            letter-spacing: 2px;
        }
        .subcategoryLink:hover
        {
            text-decoration: none;
            color: #007bff;
        }
        .categoriesAndSubcategoriesList
        {
            border-right: 2px solid #007bff;
        }
        .mealDiv .mealChildDiv
        {
            top: 0;
            background-color: rgb(255, 255, 255, 0.4);
            transform: scale(0);
            transition: 0.5s;
        }
        .mealDiv:hover .mealChildDiv
        {
            transform: scale(1);
        }
        .likeMealLink:hover
        {
            text-decoration: none;
            color: #007bff;
        }
        .commentLink:hover
        {
            text-decoration: none;
            color: #007bff;
        }
        body
        {
            background-color: #f5f5f5;
        }
    </style>

    <body>

    <?php
      include __DIR__."\layouts/nav.php";
    ?>

    <div class="py-5"></div>

    <div class="py-5"></div>

    <div class="m-auto px-3" style="width: 90%; border: 3px solid #007bff;">

    <div class="row">
        <div class="col-xl-3 categoriesAndSubcategoriesList">
            <div>

            <div class="py-2"></div>

            <ul class="categoriesList">

            <?php
                if(count($allCategories) > 0)
                {
                    for($i = 0; $i < count($allCategories); $i++)
                    {
                        // get number of subcategories per category

                        $category->setId($allCategories[$i]["id"]);

                        $noOfSubCategoriesPerCategory = $category->getNumberOfSubCategoriesPerCategory();

                        $noOfSubCategoriesPerCategoryObject = $noOfSubCategoriesPerCategory->fetch_object();

                        // get number of meals per category

                        $noOfMealsPerCategory = $category->getNumberOfMealsPerCategory();
                        $noOfMealsPerCategoryObject = $noOfMealsPerCategory->fetch_object();
                
            ?>

            <?php if((isset($noOfSubCategoriesPerCategoryObject->no_of_subcategories) && $noOfSubCategoriesPerCategoryObject->no_of_subcategories > 0) && (isset($noOfMealsPerCategoryObject->no_of_meals_per_category) && $noOfMealsPerCategoryObject->no_of_meals_per_category > 0)){ ?>

            <li class="px-3 categoryName"><?php echo $allCategories[$i]["name_en"]; ?></li>

            <!-- here -->

            <?php

                $subcategory->setCategory_id($allCategories[$i]["id"]);

                $allSubCategories = $subcategory->getSubCategoryByCategory()->fetch_all(MYSQLI_ASSOC);

                if(count($allSubCategories) > 0)
                {
                    echo "<div class='subcategoriesDiv'>";
                    for($x = 0; $x < count($allSubCategories); $x++)
                    {
                        $subcategory->setId($allSubCategories[$x]["id"]);


                        // get number of meals per subcategory

                        $noOfMealsPerSubcategory = $subcategory->getNumberOfMealsPerSubcategory();
                        $noOfMealsPerSubcategoryObject = $noOfMealsPerSubcategory->fetch_object();

            ?>

            <?php if(isset($noOfMealsPerSubcategoryObject->no_of_meals_per_subcategory) && $noOfMealsPerSubcategoryObject->no_of_meals_per_subcategory > 0){ ?>

            <a class="subcategoryLink" href="meals.php?subcategoryId=<?php echo $allSubCategories[$x]["id"]; ?>"><span class="d-block subcategoryName pl-3"><?php echo $allSubCategories[$x]["name_en"]; ?></span></a>

            <?php } ?>

            <?php
                    }echo "</div>";}
            ?>

            <?php } ?>

            <?php echo "<div class='py-2'></div>"; }} ?>

            

            </ul>

            </div>
        </div>

        <div class="col-xl-9">

        <div class="py-3"></div>

        <div class="row">
                
                    <?php
                        if(count($mealsBySubcategory) > 0)
                        {
                            for($i = 0; $i < count($mealsBySubcategory); $i++)
                            {
                                // meal rate

                                $meal->setId($mealsBySubcategory[$i]["id"]);

                                $mealRate = $meal->getMealRate();

                                $mealRateValue = $mealRate->fetch_object();

                                
                    ?>
                    <div class="col-xl-4">
                        <div>
                            <div class="position-relative mealDiv">
                                <img src="<?php echo "images/".$categoryName."/".$mealsBySubcategory[$i]["image"]; ?>" class="img-fluid w-100" style="height: 400px;">
                                <div class="position-absolute mealChildDiv w-100 h-100 text-center pt-5">
                                    <div class="pt-1"></div>
                                    <h2 style="font-size: 30px;"><?php echo $mealsBySubcategory[$i]["name_en"]; ?></h2>
                                    <h2 style="font-size: 30px;">Price: <?php echo $mealsBySubcategory[$i]["price"]; ?></h2>
                                    <h3 style="font-size: 30px;">Rate: <?php echo isset($mealRateValue->total_rate) ? round($mealRateValue->total_rate) : "0"; ?></h3>
                                    <a class="d-block" href="meal-details.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>"><button class="btn btn-primary mt-2">additional details</button></a>

                                    <?php

                                        // check if the meal is previously added to favorite list

                                        $favoritelist = new FavoriteList();

                                        $favoritelist->setUser_id($_SESSION["user"]->id);
                                        $favoritelist->setMeal_id($mealsBySubcategory[$i]["id"]);

                                        $isAddedToFavoriteList = $favoritelist->isAddedToFavoriteList();

                                        if($isAddedToFavoriteList->num_rows > 0)
                                        {
                                    ?>

                                    <a class="d-block" href="removed-from-favorite-list.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>&subcategoryId=<?php echo $_GET["subcategoryId"]; ?>"><button class="btn btn-danger mt-2">remove from favorite list</button></a>

                                    <?php }else{ ?>
                                        
                                        <a class="d-block" href="added-to-favorite-list.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>&subcategoryId=<?php echo $_GET["subcategoryId"]; ?>"><button class="btn btn-primary mt-2">add to favorite list</button></a>

                                    <?php } ?>

                                    <?php

                                        // check if the meal is previously rated

                                        $rate->setUser_id($_SESSION["user"]->id);
                                        $rate->setMeal_id($mealsBySubcategory[$i]["id"]);

                                        $isRated = $rate->read();

                                        if($isRated->num_rows == 0)
                                        {

                                    ?>
                                    <a href="meal-rate.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>"><button class="btn btn-primary mt-4">rate meal</button></a>
                                    <?php } ?>
                                    
                                </div>
                                
                            </div>
                            <div style="border: 2px solid #007bff; border-top: none;">
                                <?php

                                    // get number of likes

                                    $like->setUser_id($_SESSION["user"]->id);
                                    $like->setMeal_id($mealsBySubcategory[$i]["id"]);

                                    $numberOfLikes = $like->getNumberOfLikes()->fetch_object();
                                    
                                    $isLiked = $like->isLiked();

                                    // get number of comments

                                    $comment->setMeal_id($mealsBySubcategory[$i]["id"]);

                                    $numberOfComments = $comment->getNumberOfComments();

                                    echo "<div class='p-3 d-flex' style='justify-content: space-between;'>";

                                    if($isLiked->num_rows ==  1)
                                    {

                                ?>

                                    <a href="disliked-meal.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>&subcategoryId=<?php echo $_GET["subcategoryId"]; ?>" class="likeMealLink"><span class="text-danger">dislike</span></a>
                                
                                <?php }else{ ?>
                                    
                                    <a href="liked-meal.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>&subcategoryId=<?php echo $_GET["subcategoryId"]; ?>" class="likeMealLink"><span class="text-primary">like</span></a>

                                <?php } ?>
                                
                                <span class="text-primary">likes: <?php echo isset($numberOfLikes->number_of_likes)? $numberOfLikes->number_of_likes: "0"; ?></span>
                                </div>
                                <div class="p-3 d-flex" style="justify-content: space-between">
                                    <a href="comment.php?mealId=<?php echo $mealsBySubcategory[$i]["id"]; ?>" class="commentLink"><span class="text-primary">comment</span></a>
                                    <a class="likeMealLink"><span class="text-primary">comments: <?php echo $numberOfComments->num_rows > 0 ? $numberOfComments->fetch_object()->number_of_comments: "0"; ?></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="py-3"></div>
                    </div>

                    <?php }} ?>
            
        </div>

        </div>

    </div>

    </div>

    <div class="py-4"></div>

    <?php
        include __DIR__."\layouts\bootstrapJsFiles.php";
    ?>
    
    </body>

</html>

<?php
ob_end_flush();
?>