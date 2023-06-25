<?php

ob_start();

session_start();

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

include_once __DIR__."\app\models\Comment.php";

$title = "comment";

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

$commentIsRequired = "";

if($_POST)
{
    if(empty($_POST["comment"]))
    {
        $commentIsRequired = "please enter a comment";
    }
    else
    {
        $comment = new Comment();

        $comment->setMeal_id($_GET["mealId"]);

        $comment->setUser_id($_SESSION["user"]->id);

        $comment->setUser_comment($_POST["comment"]);

        $isInserted = $comment->create();

        $subcategoryName = $comment->getSubcategoryByMeal()->fetch_object();

        if($isInserted)
        {
            header("location:meals.php?subcategoryId=".$subcategoryName->id);
        }
        else
        {
            echo "<div class='text-danger'>Sorry Something Went Wring</div>";die;
        }
    }
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
    </style>

    <body>

        <?php
        include __DIR__."\layouts/nav.php";
        ?>
        
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="m-auto registerationDiv">
                <h2 class="text-center text-primary register_h2">Comment</h2>
                <div class="py-3"></div>
                <form class="registerationForm p-5" method="post">
                    
                    <input type="text" class="form-control" placeholder="comment" name="comment">
                    <div class="py-3"></div>

                    <div class="text-danger"><?php if(!empty($commentIsRequired)){echo "<span class='py-3'>$commentIsRequired</span>";} ?></div>

                    <div class="py-3"></div>
                    
                    <button class="btn btn-primary">comment</button>
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