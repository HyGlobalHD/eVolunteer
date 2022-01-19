<?php
include 'src/db.php';
include 'src/suggestions.php';

session_start(); // start the session

// check user already login or nah
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // show prompt already login
    header("location: homepage.php");
}

$sAPI = new suggestions();
$msg = "";
$nric = $password = "";

$msgt = "";
if (isset($_GET['msgt']) && isset($_GET['msg'])) {
    $msgt = $sAPI->msgbox($_GET['msgt'], $_GET['msg']);
    // get the message type based on the numeric value
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nric = $_POST['nric'];
    $password = $_POST['password'];

    $db = new db();
    $sAPI = new suggestions();
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

                $sql = "UPDATE user SET USER_LOGIN_COUNT = USER_LOGIN_COUNT + 1 WHERE USER_NRIC = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $nric);
                $stmt->execute();

                header("location: homepage.php?msgt=1&msg=Login Successful.");
                exit;
                //$msg = "<p style='color: #7289DA;'> Login Success. </p>";
            } else {
                $msg = $sAPI->msgbox(3, "Wrong nric or password. Please check again.");
            }
        } else {
            $msg = $sAPI->msgbox(2, "Account is not active for some reason, please contact the administrator for further information.");
        }
    } else {
        //$stmt->close();
        $msg = $sAPI->msgbox(3, "Wrong nric or password. Please check again.");
        // account not exists, but for security reason, we do not show there is no acount with this nric
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
    <link rel='icon' href='favicon.png' type='image/png' />
    <title>eVolunteer - Login</title>
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
            <li><a href="index.php" class="nav-link px-2 text-secondary">Home</a></li> <!-- text-secondary -->
            <li><a href="about.php" class="nav-link px-2 text-dark">About</a></li>
        </ul>

        <div class="text-end">
            <a href="#" class="btn btn-outline-primary me-2"><strong>Login</strong></a>
            <a href="user_register.php" class="btn btn-primary">Sign-up</a>
        </div>
    </header>
</div>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>

<body>

    <main class="form-signup">
        <?php echo $msgt; ?>

        <div class="text-center">

            <h4 style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer</span></h4>
            <h5>Account Login.</h5>
            <span id="errormsg" style="color: red;"></span>
            <span id="msg" style="color:red;"><?php echo $msg; ?></span>
            <form id="registeration-form" name="registeration-form"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="nricID" name="nric" placeholder="your NRIC"
                        autocomplete="off" required>
                    <label for="nricID">NRIC (eg: 000122886544)</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordID" name="password"
                        placeholder="your password" autocomplete="off" required>
                    <label for="passwordID">Password</label>
                </div>


                <p>New user? <a href='user_register.php' style='color:#7289DA; text-decoration: none'>Click here to
                        register.</a></p>
                <input type="submit" value="Sign In" class="w-100 btn btn-lg btn-primary" id="signup">

                <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy;
                        2021</span></p>
            </form>
        </div>


    </main>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
        </footer>
    </div>

    <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/ur.js"></script>
</body>

</html>