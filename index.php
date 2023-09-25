<?php
// Start a session to check for user authentication
session_start();

// Check if the user is logged in (has an active session)
if (isset($_SESSION['id'])) {
    $user_name = $_SESSION['name'];
    $logout_button = '<a href="logout.php" class="btn btn-danger mx-1">Logout</a>';
} else {
    // User is not logged in
    $user_name = ''; // Set to empty if no user is logged in
    // Display sign-in and register buttons
    $login_register_buttons = '
        <button class="btn btn-primary mx-1">Sign in</button>
        <button class="btn btn-primary mx-1">Register</button>
    ';
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<title>Sri Lanka POST OFFICE</title>
  </head>
  <body>
    <nav class="navbar navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="logo.png" alt="" width="750px" height="80px">
        </a>
      </div>
    </nav>
  	<nav class="navbar navbar-expand-md bg">
  		<a href="" class="navbar-brand fs-3 ms-3 text-white fs-5">Sri Lanka e-Post Office</a>
  	<button class="navbar-toggler me-3 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#btn">
  		<i class='bx bx-menu bx-md '></i>
  	</button>
  	<div class="collapse navbar-collapse ul-bg" id="btn">
  		<ul class="navbar-nav ms-auto">
  			<li class="nav-item">
  				<a href="#" class="nav-link mx-1 text-white fs-5">Home</a>
  			</li>
  			<li class="nav-item">
  				<a href="#" class="nav-link mx-1 text-white fs-5">About</a>
  			</li>
  			<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mx-1 text-white fs-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Services
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">TELEMAIL SERVICE-LOCAL</a></li>
            <li><a class="dropdown-item" href="#">(PMT Money Order)</a></li>
            <li><a class="dropdown-item" href="#">INLAND ORDINARY MONEY ORDER</a></li>
            <!--<li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>-->
          </ul>
        </li>
  			<li class="nav-item">
  				<a href="#" class="nav-link mx-1 text-white fs-5">Question</a>
  			</li>
  			<li class="nav-item">
  				<a href="#" class="nav-link mx-1 text-white fs-5">Contact</a>
  			</li>
  			
  		</ul>
  		<!-- <button class="btn btn-primary mx-1">Sign in</button>
      	<button class="btn btn-primary mx-1">Register</button> -->
        <?php if (isset($_SESSION['id'])): ?>
                <!-- <button class="btn btn-primary mx-1"> hi  <?php echo $user_name; ?></button> -->
                <span class="btn btn-primary mx-1"><?php echo $user_name; ?></span>
                <?php echo $logout_button; ?>
            <?php else: ?>
                <!-- Display sign-in and register buttons if not logged in -->
                <?php echo $login_register_buttons; ?>
            <?php endif; ?>
  	</div>
 </nav>

<!--slider images start-->

 <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active c-item">
      <img src="Images\thumb.png" class="d-block w-100 c-img" alt="">
      <!--<div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>Some representative placeholder content for the first slide.</p>
      </div>-->
    </div>
    <div class="carousel-item c-item">
      <img src="Images\pic.png" class="d-block w-100 c-img" alt="">
      <!--<div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Some representative placeholder content for the second slide.</p>
      </div>-->
    </div>
    <div class="carousel-item c-item">
      <img src="Images\telemail.png" class="d-block w-100 c-img" alt="">
      <!--<div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>-->
    </div>
    <div class="carousel-item c-item">
      <img src="Images\thumb2.jpg" class="d-block w-100 c-img" alt="">
      <!--<div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>-->
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
