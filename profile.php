<?php

ob_start();

session_start();

if(!isset($_SESSION["user"]))
{
    header("location:index.php");die;
}

$title = $_SESSION["user"]->first_name." ".$_SESSION["user"]->last_name;

include_once __DIR__."\app\models\User.php";

$user = new User();

$user->setEmail($_SESSION["user"]->email);

$singleUser = $user->getSingleUser()->fetch_object();

$allFieldsAreRequired = "";
$imageIsToLarge = "";
$allowedExtensions = "";
$sorrySomethingWentWring = "";
$updatedSuccessfully = "";

$photoName = "";

if($_POST)
{
    if(empty(trim($_POST["first_name"])) ||
    empty(trim($_POST["last_name"])) ||
    empty(trim($_POST["phone"])) ||
    empty(trim($_POST["gender"])))
    {
        $allFieldsAreRequired = "All Fields Are Required";
    }

    //print_r($_FILES);die;

    if($_FILES["image"]["error"] == "0")
    {
        // photo exists

        // size

        $maxUploadSize = 10**6;
        $megaBytes = $maxUploadSize / (10**6);

        if($_FILES["image"]["size"] > $maxUploadSize)
        {
            $imageIsToLarge = "Maximum image size is $megaBytes bytes";
        }

        // extension

        $extension = pathinfo($_FILES["image"]["name"] , PATHINFO_EXTENSION);
        $availableExtensions = ["jpg" , "png" , "jpeg"];

        if(!in_array($extension , $availableExtensions))
        {
            $allowedExtensions = "Allowed extensions are ".implode("," , $availableExtensions);
        }
        // set image
        if(empty($imageIsToLarge) && empty($allowedExtensions))
        {
            $photoName = uniqid().".".$extension;
            $photoPath = "images/users/".$photoName;
            move_uploaded_file($_FILES["image"]["tmp_name"] , $photoPath);

            $_SESSION["user"]->image = $photoName;
        }
    }
    if(empty($allFieldsAreRequired) && empty($imageIsToLarge) && empty($allowedExtensions))
    {
        $user->setFirst_name($_POST["first_name"]);
        $user->setLast_name($_POST["last_name"]);
        $user->setPhone($_POST["phone"]);
        $user->setGender($_POST["gender"]);
        $user->setImage($photoName);

        $isUpdated = $user->update();

        if($isUpdated)
        {
            $updatedUser = $user->getSingleUser()->fetch_object();
            $singleUser = $updatedUser;
            $_SESSION["user"]->first_name = $_POST["first_name"];
            $_SESSION["user"]->last_name = $_POST["last_name"];
            $_SESSION["user"]->phone = $_POST["phone"];
            $_SESSION["user"]->gender = $_POST["gender"];
            
            $updatedSuccessfully = "Updated successfully";
            
            //print_r($_SESSION);die;
        }
        else
        {
            $sorrySomethingWentWring = "Sorry something went wrong<br>please try again later";
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

        <div class="py-5"></div>
        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
            <div class="m-auto registerationDiv">
                <h2 class="text-center text-primary register_h2">Update My Profile</h2>
                <div class="py-1"></div>
                <form class="registerationForm p-4" method="post" enctype="multipart/form-data">

                    <div class="w-100 d-flex justify-content-center">
                        <img src="<?php echo "images/users/".$singleUser->image; ?>" class="img-fluid userImage" style="width: 250px; height: 200px; cursor: pointer">
                        <input type="file" name="image" class="imageInput d-none">
                    </div>
                    <div class="py-3"></div>
                    
                    <div class="text-center" style="color: red"><?php if(!empty($imageIsToLarge)){echo $imageIsToLarge;} ?></div>
                    <div class="text-center" style="color: red"><?php if(!empty($allowedExtensions)){echo $allowedExtensions;} ?></div>

                    
                    <div class="py-2">

                        <div class="w-100 row">

                            <div class="col-xl-6">
                                <div>
                                    <input type="text" class="form-control" placeholder="first name" name="first_name" value="<?php echo $singleUser->first_name; ?>">
                                    <div class="py-3"></div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div>
                                    <input type="text" class="form-control" placeholder="last name" name="last_name" value="<?php echo $singleUser->last_name; ?>">
                                    <div class="py-3"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row w-100">

                            <div class="col-xl-12">
                                <div>
                                    <input type="number" class="form-control" placeholder="phone" name="phone" value="<?php echo $singleUser->phone; ?>">
                                    <div class="py-3"></div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="text-center" style="color: red"><?php if(!empty($allFieldsAreRequired)){echo $allFieldsAreRequired;} ?></div>
                    <div class="text-center" style="color: red"><?php if(!empty($sorrySomethingWentWring)){echo $sorrySomethingWentWring;} ?></div>
                    <div class="text-center text-success"><?php if(!empty($updatedSuccessfully)){echo $updatedSuccessfully;} ?></div>

                    

                    <label>gender</label>
                    <div class="py-2"></div>
                    <label class="mr-2">male</label><input type="radio" name="gender" value="m" <?php if($singleUser->gender == "m"){echo "checked";} ?>><br><br>
                    <label class="mr-2">female</label><input type="radio" name="gender" value="f" value="m" <?php if($singleUser->gender == "f"){echo "checked";} ?>>
                    <div class="py-3"></div>
                    
                    <button class="btn btn-primary">update</button>
                </form>
            </div>
        </div>
        <div class="py-1"></div>


        <?php
            include __DIR__."\layouts\bootstrapJsFiles.php";
        ?>

        <script>

            $(".userImage").click(function()
            {
                $(".imageInput").click();
            })

        </script>

    </body>

</html>

<?php
ob_end_flush();
?>