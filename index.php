<?php
include 'src/db.php';
include 'src/users.php';

session_start(); // start the session

// TODO check user already login or nah
$msg = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="css/signup.css" rel="stylesheet">
    <style>
        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .form-control-dark {
            color: #fff;
            background-color: var(--bs-dark);
            border-color: var(--bs-gray);
        }

        .form-control-dark:focus {
            color: #fff;
            background-color: var(--bs-dark);
            border-color: #fff;
            box-shadow: 0 0 0 .25rem rgba(255, 255, 255, .25);
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .text-small {
            font-size: 85%;
        }

        .dropdown-toggle {
            outline: 0;
        }

        body {
            display: block;
        }
    </style>
</head>

<div class="p-3 bg-white text-dark">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul></ul>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 text-secondary"><strong>Home</strong></a></li> <!-- text-secondary -->
            <li><a href="#" class="nav-link px-2 text-dark">Features</a></li>
            <li><a href="#" class="nav-link px-2 text-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-dark">About</a></li>
        </ul>

        <div class="text-end">
            <a href="user_login.php" class="btn btn-outline-primary me-2">Login</a>
            <a href="user_register.php" class="btn btn-primary" style="text-decoration: none;">Sign-up</a>
        </div>
    </header>
</div>


<body>

    <main class="form-signup">

        <div class="text-center">

            <h4 style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer</span></h4>
            <h5>A place where everyone gather together as a community.</h5>
            <p>Join us today and be a volunteer!</p>
            <a href="user_register.php" class="btn btn-primary">Sign-up Now</a>
            <p></p>
            <p>Already have an account? <a href="user_login.php">Login Now!</a></p>
            
        <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy; 2021</span></p>
        </div>


    </main>

    <br><br><br><br>
    <br>
    <div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
  </footer>
</div>
    

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/ur.js"></script>
</body>


</html>