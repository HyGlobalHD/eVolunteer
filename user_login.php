<?php
include 'src/db.php';

session_start(); // start the session

// TODO check user already login or nah

$msg = "";
$nric = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nric = $_POST['nric'];
    $password = $_POST['password'];

    $db = new db();
    $conn = $db->connect();

    $checkExist = "SELECT USER_NRIC FROM user WHERE USER_NRIC = ? LIMIT 1";

    $stmt = $conn->prepare($checkExist);
    $stmt->bind_param("s", $nric);
    $stmt->execute();
    $stmt->bind_result($nric);
    $stmt->store_result();
    $rnum = $stmt->num_rows;
    if ($rnum == 1) {
        $stmt->close();

        $checkpass = "SELECT USER_PASSWORD, USER_STATUS, GROUP_CODE FROM user WHERE USER_NRIC = ?";
        //$pass_hash = password_hash($password, PASSWORD_ARGON2I);

        $stmt = $conn->prepare($checkpass);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($passwordDB, $status, $groupcode);
        $stmt->fetch();

        if ($status === "A") {
            if (password_verify($password, $passwordDB)) {

                $_SESSION["loggedin"] = true;
                $_SESSION["nric"] = $nric;
                $_SESSION["groupcode"] = $groupcode;

                header("location: homepage.php");
                exit;
                $msg = "<p style='color: #7289DA;'> Login Success. </p>";
            } else {
                $msg = "Wrong nric or password. Please check again.";
            }
        } else {
            $msg = "Account is not active";
        }
    } else {
        //$stmt->close();
        $msg = "The user in non-existing. Please go to sign-up section, to register.";
    }
    $stmt->close();
    $conn->close();
}

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
            <li><a href="index.php" class="nav-link px-2 text-dark">Home</a></li> <!-- text-secondary -->
            <li><a href="#" class="nav-link px-2 text-dark">Features</a></li>
            <li><a href="#" class="nav-link px-2 text-dark">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-dark">About</a></li>
        </ul>

        <div class="text-end">
            <a href="user_login.php" class="btn btn-outline-primary me-2"><strong>Login</strong></a>
            <a href="#" class="btn btn-primary">Sign-up</a>
        </div>
    </header>
</div>


<body>

    <main class="form-signup">

        <div class="text-center">

            <h4 style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer</span></h4>
            <h5>Account Login.</h5>
            <span id="errormsg" style="color: red;"></span>
            <span id="msg" style="color:red;"><?php echo $msg; ?></span>
            <form id="registeration-form" name="registeration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="nricID" name="nric" placeholder="your NRIC" autocomplete="off" required>
                    <label for="nricID">NRIC (eg: 000122886544)</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordID" name="password" placeholder="your password" autocomplete="off" required>
                    <label for="passwordID">Password</label>
                </div>


                <p>New user? <a href='user_register.php' style='color:#7289DA; text-decoration: none'>Click here to register.</a></p>
                <input type="submit" value="Sign In" class="w-100 btn btn-lg btn-primary" id="signup">

                <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy; 2021</span></p>
            </form>
        </div>


    </main>

    <div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
  </footer>
</div>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/ur.js"></script>
</body>

</html>