<nav class="navbar navbar-expand-lg bg-white fixed-top" style="box-shadow: 0 0 29px 0 rgb(68 88 144 / 12%);">
  <div class="container">
    <a class="navbar-brand" href="brass-tacks.php">Brass Tacks</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
        <?php if(!isset($_SESSION["user"])){ ?>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <?php } ?>

        <?php if(isset($_SESSION["user"])){ ?>
        <li class="nav-item authenticationNavItem mx-2">
          <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item authenticationNavItem mx-2">
          <a class="nav-link" href="meals.php?subcategoryId=1">Meals</a>
        </li>
        <li class="nav-item authenticationNavItem mx-2">
          <a class="nav-link" href="my-favorite-meals.php">My favorite meals</a>
        </li>
        <li class="nav-item authenticationNavItem mx-2">
          <a class="nav-link" href="order.php">make an order</a>
        </li>
        <li class="nav-item authenticationNavItem mx-2">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
        <li class="nav-item authenticationNavItem mx-2" style="border-left: 2px solid #007bff;">
          <a class="nav-link" href="profile.php"><?php echo ucwords($_SESSION["user"]->first_name." ".$_SESSION["user"]->last_name); ?></a>
        </li>
        <?php } ?>
        
      </ul>
      
    </div>
  </div>
</nav>