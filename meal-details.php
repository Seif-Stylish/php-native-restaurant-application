<?php

ob_start();

session_start();

include_once __DIR__."/app/models/Meal.php";
include_once __DIR__."/app/models/Comment.php";
include_once __DIR__."/app/models/Like.php";

// getNumberOfLikes

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

$title = "meal details";

$meal = new Meal();

$meal->setId($_GET["mealId"]);

$mealRate = "";

$mealRateObject = "";

$singlemealDetails = $meal->read();

$singlemealDetailsObject = "";

$mealDetailsObject = "";

if($singlemealDetails->num_rows > 0)
{
    $mealDetails = $meal->getMealDetails();

    $mealDetailsObject = $mealDetails->fetch_object();

    $singlemealDetailsObject = $singlemealDetails->fetch_object();

    $mealRate = $meal->getMealRate();

    $mealRateObject = $mealRate->fetch_object();

    // single meal details
}
else
{
    header("location:home.php");
}

// meal comments

$comment = new Comment();

$comment->setMeal_id($_GET["mealId"]);

$mealComments = $comment->read();

$mealCommentsArray = "";

$showCommentsButton = "";

$hideCommentsButton = "";

if($mealComments->num_rows > 0)
{
    $mealCommentsArray = $mealComments->fetch_all(MYSQLI_ASSOC);

    $showCommentsButton = "<button class='btn btn-primary showCommentsButton mt-4'>show comments</button>";

    $hideCommentsButton = "<button class='btn btn-danger hideCommentsButton mt-4'>hide comments</button>";
}

$date = date_create("$mealDetailsObject->created_at");

// get number of likes per meal

$like = new Like();

$like->setMeal_id($_GET["mealId"]);

$likes_per_meal = $like->getNumberOfLikes();

//print_r($likes_per_meal);die;

$likes_per_meal_object = $likes_per_meal->fetch_object();

?>

<html>

    <?php
        include __DIR__."\layouts\bootstrapCssFiles.php";
    ?>

    <style>
        .mealDetailsSpan
        {
            font-size: 30px;
            letter-spacing: 1px;
        }
        .detailTypeSpan
        {
            font-weight: 700;
        }
        .mealDescriptionText
        {
            color: #4d5156;
        }
        .hideCommentsButton
        {
            display: none;
        }
        .commentsDiv
        {
            display: none;
        }
    </style>
    <body>

    <?php
      include __DIR__."\layouts/nav.php";
    ?>

    <div class="py-5 my-3"></div>

    <div class="d-flex justify-content-center align-items-center">
        <div class="container p-3" style="border: 2px solid #007bff;">
            <div>
                <img src="images/<?php echo $mealDetailsObject->category_name."/".$mealDetailsObject->image; ?>" class="w-100 img-fluid" style="height: 500px;">
                <div class="mt-3" style="border-bottom: 2px solid #007bff;"></div>
            </div>
            <div class="row py-3 mealDescriptionText">


                <div class="col-xl-6">
                    <div class="p-3">
                        <span class="mealDetailsSpan">Meal Details</span>

                        <div class="myProgressBar my-2 w-50">
                            <div class="progress" style="height: 1px;">
                                <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress" style="height: 1px;">
                                <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="mt-2"><span><span class="detailTypeSpan">Name:</span> <?php echo $singlemealDetailsObject->name_en; ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Price:</span> <?php echo $singlemealDetailsObject->price; ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Category:</span> <?php echo $mealDetailsObject->category_name; ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Subcategory:</span> <?php echo $mealDetailsObject->subcategory_name; ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Released at:</span> <?php echo date_format($date,"F Y"); ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Rate:</span> <?php echo isset($mealRateObject->total_rate)? round($mealRateObject->total_rate): "0"; ?></span></div>
                        <div class="mt-2"><span><span class="detailTypeSpan">Likes:</span> <?php echo isset($likes_per_meal_object->number_of_likes)? $likes_per_meal_object->number_of_likes: "0"; ?></span></div>

                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="p-3">

                    <span class="mealDetailsSpan">Description</span>

                    <div class="myProgressBar my-2 w-50">
                        <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <p><?php echo $singlemealDetailsObject->desc_en; ?></p>

                    </div>
                </div>

                <div class="px-4">
                    <?php if(!empty($showCommentsButton)){echo $showCommentsButton;} ?>
                    <?php if(!empty($hideCommentsButton)){echo $hideCommentsButton;} ?>
                </div>

            </div>
        </div>
    </div>

    <div class="p-4"></div>

    <div class="commentsDiv container p-4" style="border: 2px solid #007bff">
        <?php
            if(!empty($mealCommentsArray))
            {
                for($i = 0; $i < count($mealCommentsArray); $i++)
                {

        ?>

        <div class="py-2" style="border-bottom: 1px solid #007bff;">
            <?php if(!empty($_SESSION["user"])){ ?> <h3><?php echo $mealCommentsArray[$i]["first_name"]." ".$mealCommentsArray[$i]["last_name"]; ?></h3><?php } ?>
            <p><?php echo $mealCommentsArray[$i]["comment"]; ?></p>
        </div>

        <?php }} ?>

        <div class="p-1 endIndicator"></div>
    </div>

    <div class="p-4"></div>

    <?php
        include __DIR__."\layouts\bootstrapJsFiles.php";
    ?>
    
    <script>
        var showCommentsButton = document.querySelector(".showCommentsButton");
        var hideCommentsButton = document.querySelector(".hideCommentsButton");

        showCommentsButton.addEventListener("click" , showMealComments);
        hideCommentsButton.addEventListener("click" , hideMealComments);

        function showMealComments()
        {
            $(".commentsDiv").fadeIn(500);
            var endOfDiv = $(".endIndicator").offset().top;
            $("html , body").animate({scrollTop : endOfDiv} , 1000);
            $(showCommentsButton).fadeOut(0);
            $(hideCommentsButton).fadeIn(300);
        }

        function hideMealComments()
        {
            $(".commentsDiv").fadeOut(500);
            $(hideCommentsButton).fadeOut(0);
            $(showCommentsButton).fadeIn(300);
        }
        
    </script>
    </body>
</html>


<?php
ob_end_flush();
?>