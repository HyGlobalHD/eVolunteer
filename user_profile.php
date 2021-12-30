<?php
// todo user profile

// include libraries
include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

// start session
session_start();

// check if user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
} else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
}

// declare new
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

// get session nric
$currUser = $_SESSION["nric"];

// variables
$userProfile = "";
$msgt = "";
$username = "";
$nric = "";
$fullname = "";
$email = "";
$phoneno = "";
$createddate = "";
$logincount = "";
$userstatus = "";
$groupcode = "";
// msgbox
if (isset($_GET['msgt']) && isset($_GET['msg'])) {
  $msgt = $msgt . $sAPI->msgbox($_GET['msgt'], $_GET['msg']);
  // get the message type based on the numeric value
}

// an option to check get username if set or not
if (isset($_GET['username']) && strlen(trim($_GET['username'])) > 0) {
    $searchUsername = $_GET['username'];
    // show the search username details
    $searchUsernameNRIC = $uAPI->getUserNRIC($searchUsername); // although it only have one input, it still from an array
    if(!(is_null($searchUsernameNRIC))) {
        foreach($searchUsernameNRIC as $searchUsernameNRICs) {
            $searchUsernameNRIC = $searchUsernameNRICs['USER_NRIC'];
        }
        $userdetail = $uAPI->getUserDetails($searchUsernameNRIC);
        if(!(is_null($userdetail))) {
            foreach ($userdetail as $userdetails) {
                // USER_NRIC, USER_USERNAME, USER_FULL_NAME, USER_EMAIL, USER_PHONE_NO, USER_CREATED_DATE, USER_LOGIN_COUNT, USER_STATUS, GROUP_CODE
                $username = $userdetails['USER_USERNAME'];
                $nric = $userdetails['USER_NRIC'];
                $fullname = $userdetails['USER_FULL_NAME'];
                $email = $userdetails['USER_EMAIL'];
                $phoneno = $userdetails['USER_PHONE_NO'];
                $createddate = $userdetails['USER_CREATED_DATE'];
                $logincount = $userdetails['USER_LOGIN_COUNT'];
                $userstatus = $userdetails['USER_STATUS'];
                $groupcode = $userdetails['GROUP_CODE'];
            
            
            }
        } else {
            // prompt something wrong happened // bcs the user exist but there was something wrong happened
            $msgt = $msgt . $sAPI->msgbox(3, "Opsie!! Something went wrong. Please try again.");
        }
    } else {
        // prompt not exist
        $msgt = $msgt . $sAPI->msgbox(2, "The user you looking for does not exist.");
    }


} else {
    // show current user profile
    $userdetail = $uAPI->getUserDetails($currUser);
    if(!(is_null($userdetail))) {
        foreach ($userdetail as $userdetails) {
            // USER_NRIC, USER_USERNAME, USER_FULL_NAME, USER_EMAIL, USER_PHONE_NO, USER_CREATED_DATE, USER_LOGIN_COUNT, USER_STATUS, GROUP_CODE
            $username = $userdetails['USER_USERNAME'];
            $nric = $userdetails['USER_NRIC'];
            $fullname = $userdetails['USER_FULL_NAME'];
            $email = $userdetails['USER_EMAIL'];
            $phoneno = $userdetails['USER_PHONE_NO'];
            $createddate = $userdetails['USER_CREATED_DATE'];
            $logincount = $userdetails['USER_LOGIN_COUNT'];
            $userstatus = $userdetails['USER_STATUS'];
            $groupcode = $userdetails['GROUP_CODE'];
        
        
        }
    }
}

/**
 * so, what will the user profile page show?
 * 1. the details of the user in a beautiful design layout ( option edit user profile // for current user only // check if the search user is the current user)
 * 2. the list of the user's suggestions
 * 3. the list of the user's suggestions that are choosen to be volunteer program
 * 4. the list of volunteer program that user have participated including the ongoing, upcoming, and past
 * 5. the list of user achievements
 * 
 */

 ?>
 
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Document</title>

  <link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  <meta name="theme-color" content="#7952b3">


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <link href="css/offcanvas.css" rel="stylesheet">
  <link href="css/dropdown.css" rel="stylesheet">
</head>

<body class="bg-light">

  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
    <div class="container-fluid">
      <span class="navbar-brand" style="pointer-events: none;cursor: default;"><span style="color: #7289DA;">e</span>Volunteer</span>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Homepage</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Suggestions</a>
            <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
              <li><a class="dropdown-item" href="top_suggestions.php">Top Suggestions</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="recent_suggestions.php">Recent Suggestions</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-success" href="create_suggestions.php">Create Suggestions</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="search_suggestions.php">Search Suggestions</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Volunteer Program</a>
            <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
              <li><a class="dropdown-item" href="volunteer_program.php">All Volunteer Program</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="search_volunteer.php">Search Volunteer Program</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="participant_status.php">Participation Status</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
            <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
              <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
              <li><a class="dropdown-item" href="#">Achievement</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item dropdown-item-danger d-flex gap-2 align-items-center" href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
  </svg>


  <main class="container">
    <div class="d-flex align-items-center p-3 my-3 text-white bg-dark rounded shadow-sm">
      <div class="lh-1">
        <h1 class="h6 mb-0 text-white lh-1"><span style="color: #7289DA;">e</span>Volunteer</h1>
        <small>User Profile - <?php echo $username;  ?></small>
      </div>
    </div>

    <?php echo $msgt; ?>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Top 3 Suggestions</h6>
      <small class="d-block text-end mt-3">
      </small>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Recent Suggestions</h6>
      <small class="d-block text-end mt-3">
      </small>
    </div>

    <div class="text-center">
      <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy; 2021</span></p>
    </div>
  </main>

  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
      <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
    </footer>
  </div>

  <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  <script src="js/offcanvas.js"></script>
</body>

</html>