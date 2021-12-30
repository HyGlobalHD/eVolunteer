<?php
// todo user profile

// include libraries
include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';
include 'src/group.php';
include 'src/achievement.php';

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
$gAPI = new group();
$aAPI = new achievement();

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
$checkEditable = "";
// msgbox
if (isset($_GET['msgt']) && isset($_GET['msg'])) {
    $msgt = $msgt . $sAPI->msgbox($_GET['msgt'], $_GET['msg']);
    // get the message type based on the numeric value
}

$usertopsuggestions = $userrecentsuggestions = $uacontent = "";

// an option to check get username if set or not
if (isset($_GET['username']) && strlen(trim($_GET['username'])) > 0) {
    $searchUsername = $_GET['username'];
    // show the search username details
    $searchUsernameNRIC = $uAPI->getUserNRIC($searchUsername); // although it only have one input, it still from an array
    if (!(is_null($searchUsernameNRIC))) {
        foreach ($searchUsernameNRIC as $searchUsernameNRICs) {
            $searchUsernameNRIC = $searchUsernameNRICs['USER_NRIC'];
        }
        $userdetail = $uAPI->getUserDetails($searchUsernameNRIC);
        if (!(is_null($userdetail))) {
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

                $utsd = $sAPI->getUserTopSuggestionsLimitOrder($nric, 0, 3, 'DESC');
                if (!(is_null($utsd))) {
                    foreach ($utsd as $utsds) {
                        $sId = $utsds['SUGGESTIONS_ID'];
                        $sTitle = $utsds['SUGGESTIONS_TITLE'];
                        $sDetails = $utsds['SUGGESTIONS_DETAILS'];
                        $sCreatedDate = $utsds['SUGGESTIONS_CREATED_DATE'];
                        $cCreatedBy = $utsds['USER_NRIC'];
                        $userUsername = $uAPI->getUserUsername($cCreatedBy);
                        $voteCount = $sAPI->getVote($sId);
                        $usertopsuggestions = $usertopsuggestions . "<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
                    }
                }
                $ursd = $sAPI->getUserRecentSuggestionsLimitOrder($nric, 0, 3, 'DESC');
                if (!(is_null($ursd))) {
                    foreach ($ursd as $ursds) {
                        $sId = $ursds['SUGGESTIONS_ID'];
                        $sTitle = $ursds['SUGGESTIONS_TITLE'];
                        $sDetails = $ursds['SUGGESTIONS_DETAILS'];
                        $sCreatedDate = $ursds['SUGGESTIONS_CREATED_DATE'];
                        $cCreatedBy = $ursds['USER_NRIC'];
                        $userUsername = $uAPI->getUserUsername($cCreatedBy);
                        $voteCount = $sAPI->getVote($sId);
                        $userrecentsuggestions = $userrecentsuggestions . "<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
                    }
                }

                $ualist = $aAPI->getUserAchievement($nric);
                if (!(is_null($ualist))) {
                    foreach ($ualist as $ualists) {
                        // USER_NRIC	ACHIEVEMENT_ID	RECEIVED_DATE -> user_achievement
                        $a_id = $ualists['ACHIEVEMENT_ID'];
                        $a_received = $ualists['RECEIVED_DATE'];

                        // ACHIEVEMENT_ID	ACHIEVEMENT_NAME	ACHIEVEMENT_DESCRIPTION	ACHIEVEMENT_CREATED_DATE USER_NRIC        
                        $adetail = $aAPI->getAchievementDetails($a_id);
                        if (!(is_null($adetail))) {
                            foreach ($adetail as $adetails) {
                                $a_name = $adetails['ACHIEVEMENT_NAME'];
                                $a_desc = $adetails['ACHIEVEMENT_DESCRIPTION'];
                                $a_created = $adetails['ACHIEVEMENT_CREATED_DATE'];
                                $a_created_by = $adetails['USER_NRIC'];

                                $uacontent = $uacontent . "<div class='d-flex text-muted pt-3'><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class=''><strong class='text-primary'>" . $a_name . "</strong><br><span>" . $a_desc . "</span></div><span class='d-block text-muted'>Recieved on " . $a_received . "</span><span></span></div></div>";
                            }
                        }
                    }
                } else {
                    $uacontent = "There is no achievement yet.";
                }
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
    if (!(is_null($userdetail))) {
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

            $utsd = $sAPI->getUserTopSuggestionsLimitOrder($nric, 0, 3, 'DESC');
            if (!(is_null($utsd))) {
                foreach ($utsd as $utsds) {
                    $sId = $utsds['SUGGESTIONS_ID'];
                    $sTitle = $utsds['SUGGESTIONS_TITLE'];
                    $sDetails = $utsds['SUGGESTIONS_DETAILS'];
                    $sCreatedDate = $utsds['SUGGESTIONS_CREATED_DATE'];
                    $cCreatedBy = $utsds['USER_NRIC'];
                    $userUsername = $uAPI->getUserUsername($cCreatedBy);
                    $voteCount = $sAPI->getVote($sId);
                    $usertopsuggestions = $usertopsuggestions . "<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
                }
            }
            $ursd = $sAPI->getUserRecentSuggestionsLimitOrder($nric, 0, 3, 'DESC');
            if (!(is_null($ursd))) {
                foreach ($ursd as $ursds) {
                    $sId = $ursds['SUGGESTIONS_ID'];
                    $sTitle = $ursds['SUGGESTIONS_TITLE'];
                    $sDetails = $ursds['SUGGESTIONS_DETAILS'];
                    $sCreatedDate = $ursds['SUGGESTIONS_CREATED_DATE'];
                    $cCreatedBy = $ursds['USER_NRIC'];
                    $userUsername = $uAPI->getUserUsername($cCreatedBy);
                    $voteCount = $sAPI->getVote($sId);
                    $userrecentsuggestions = $userrecentsuggestions . "<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
                }
            }

            $ualist = $aAPI->getUserAchievement($nric);
            if (!(is_null($ualist))) {
                foreach ($ualist as $ualists) {
                    // USER_NRIC	ACHIEVEMENT_ID	RECEIVED_DATE -> user_achievement
                    $a_id = $ualists['ACHIEVEMENT_ID'];
                    $a_received = $ualists['RECEIVED_DATE'];

                    // ACHIEVEMENT_ID	ACHIEVEMENT_NAME	ACHIEVEMENT_DESCRIPTION	ACHIEVEMENT_CREATED_DATE USER_NRIC        
                    $adetail = $aAPI->getAchievementDetails($a_id);
                    if (!(is_null($adetail))) {
                        foreach ($adetail as $adetails) {
                            $a_name = $adetails['ACHIEVEMENT_NAME'];
                            $a_desc = $adetails['ACHIEVEMENT_DESCRIPTION'];
                            $a_created = $adetails['ACHIEVEMENT_CREATED_DATE'];
                            $a_created_by = $adetails['USER_NRIC'];

                            $uacontent = $uacontent . "<div class='d-flex text-muted pt-3'><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class=''><strong class='text-primary'>" . $a_name . "</strong><br><span>" . $a_desc . "</span></div><span class='d-block text-muted'>Recieved on " . $a_received . "</span><span></span></div></div>";
                        }
                    }
                }
            } else {
                $uacontent = "There is no achievement yet.";
            }
        }
    }
}

$msgt = $msgt . $sAPI->msgbox(0, "All the listed below are only part of it and not all of it.");

if ($uAPI->getUserUsername($currUser) !== $username) {
    $msgt = $msgt . $sAPI->msgbox(0, "You are currently viewing other people's profile. <a class='text-primary' style='text-decoration: none;' href='user_profile.php'>Check here to see your own profile.</a>");
} else {
    $checkEditable = "<a href='edit_user_profile.php' class='text-primary' style='text-decoration: none;'>Edit Profile</a>";
}

if ($userstatus === "A") {
    $userstatus = "Active";
} else if ($userstatus === "I") {
    $userstatus = "Inactive";
} else if ($userstatus === "B") {
    $userstatus = "Banned";
}

$groupcode = $gAPI->getGroupName($groupcode);
/**
 * list of sensitive data to omitted from the user profile // for search mostly
 * nric, fullname, email, phoneno
 * 
 * list of not sensitive data
 * username, createddate, logincount, userstatus, groupcode
 */

/**
 * so, what will the user profile page show?
 * 1. the details of the user in a beautiful design layout ( option edit user profile // for current user only // check if the search user is the current user)
 * 2. the list of the user's suggestions
 * 3. the list of the user's suggestions that are choosen to be volunteer program // omiit out for now // for future plan
 * 4. the list of volunteer program that user have participated including the ongoing, upcoming, and past // for security reason, we don't show the it at all // will considered if user want to see it
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
            <h6 class="border-bottom pb-2 mb-0">User Details:</h6>
            <div class="d-flex text-muted pt-3">
                <div class="pb-3 mb-0 lh-sm border-bottom w-100">
                    <div class="">
                        <strong class="text-dark">Username: </strong>
                        <span class="text-dark"> <?php echo $username; ?></span>
                    </div>
                    <div class="">
                        <strong class="text-dark">Created On: </strong>
                        <span class="text-dark"> <?php echo $createddate; ?></span>
                    </div>
                    <div class="">
                        <strong class="text-dark">Total login: </strong>
                        <span class="text-dark"> <?php echo $logincount; ?></span>
                    </div>
                    <div class="">
                        <strong class="text-dark">Current Status: </strong>
                        <span class="text-dark"> <?php echo $userstatus; ?></span>
                    </div>
                    <div class="">
                        <strong class="text-dark">Group Type: </strong>
                        <span class="text-dark"> <?php echo $groupcode; ?></span>
                    </div>
                </div>
            </div>
            <small class="d-block text-end mt-3">
                <?php echo $checkEditable; ?>
            </small>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">List of Suggestions Given: </h6>
            <small class="d-block mt-3">
                Top Voted Suggestions:
            </small>
            <?php echo $usertopsuggestions; ?>
            <small class="d-block mt-3">
                Recent Suggestions:
            </small>
            <?php echo $userrecentsuggestions; ?>
            <small class="border-top d-block text-end mt-3">
                <a class="text-primary" style="text-decoration: none;" href="search_suggestions.php?username=<?php echo $username; ?>">Search more</a>
            </small>
        </div>
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">List of User Achievement:</h6>
            <?php echo $uacontent; ?>
            <small class="d-block mt-3">
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