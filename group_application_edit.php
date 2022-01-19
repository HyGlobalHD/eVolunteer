<?php

// start session
session_start();
// include db.php
include 'src/db.php';
// include suggestions.php
include 'src/suggestions.php';
// include users.php
include 'src/users.php';
// include achievement.php
include 'src/achievement.php';
// include group.php
include 'src/group.php';

// check if user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
} else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
}

// check if user is adm groupcode
if ($_SESSION["groupcode"] != "ADM") {
    header("location: homepage.php?msgt=2&msg=You are not allowed to access this page.");
    exit;
}

// api
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();
$aAPI = new achievement();
$gAPI = new group();

$msgt = "";
$errmsg = "";

// current user
$currUser = $_SESSION["nric"];

// get user nric from $_GET
$nric = $_GET['nric'];

$app_user_nric = "";
$app_group_code = "";
$app_date = "";
$app_status = "";
$app_accept_by = "";

// get group application by $nric
$appdata = $gAPI->getGroupApplication($nric);
if (!(is_null($appdata))) {
    foreach ($appdata as $appdatas) {
        $app_user_nric = $appdatas['USER_NRIC'];
        $app_group_code = $appdatas['GROUP_CODE'];
        $app_date = $appdatas['APP_DATE'];
        $app_status = $appdatas['APP_STATUS'];
        $app_accept_by = $appdatas['APP_ACCEPT_BY'];

        $app_user_name = $uAPI->getUserUsername($app_user_nric);
        $gname = $gAPI->getGroupName($app_group_code);
    }
} else {
    $errmsg = "No application found.";
}
// editable data:
// status

if (isset($_POST["updateUserApp"])) {
    $post_status = $_POST["appstatus"];

    if ($post_status == "P") {
        $msgt = $msgt . $sAPI->msgbox(3, "Please select a status.");
    } else {
        if ($gAPI->updateGroupApplication($nric, $app_group_code, $post_status, $currUser)) {
            $msgt = $msgt . $sAPI->msgbox(1, "Successfully updated application.");

            if ($post_status == "R") {
                // do nothing
            } else if ($post_status == "A") {
                if ($gAPI->updateUserGroup($nric, $app_group_code)) {
                    $msgt = $msgt . $sAPI->msgbox(1, "Successfully updated user group.");
                } else {
                    $msgt = $msgt . $sAPI->msgbox(2, "Failed to update user group.");
                }
            }
        } else {
            $msgt = $msgt . $sAPI->msgbox(2, "Failed to update application.");
        }
    }
}
if (isset($_POST["deleteUserApp"])) {
    if ($gAPI->deleteGroupApplication($nric)) {
        $msgt = $msgt . $sAPI->msgbox(1, "Successfully deleted application.");
    } else {
        $msgt = $msgt . $sAPI->msgbox(2, "Failed to delete application.");
    }
}
// get group application by $nric
$appdata = $gAPI->getGroupApplication($nric);
if (!(is_null($appdata))) {
    foreach ($appdata as $appdatas) {
        $app_user_nric = $appdatas['USER_NRIC'];
        $app_group_code = $appdatas['GROUP_CODE'];
        $app_date = $appdatas['APP_DATE'];
        $app_status = $appdatas['APP_STATUS'];
        $app_accept_by = $appdatas['APP_ACCEPT_BY'];

        $app_user_name = $uAPI->getUserUsername($app_user_nric);
        $gname = $gAPI->getGroupName($app_group_code);
    }
} else {
    $errmsg = "No application found.";
}




?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='favicon.png' type='image/png' />
    <title>eVolunteer - Group Application</title>

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
                        <a class="nav-link" aria-current="page" href="homepage.php">Homepage</a>
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
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="view_achievement.php">Achievement</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item active" href="group_apply.php">Group Application</a></li>
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
                <small>Group Application</small>
            </div>
        </div>

        <?php echo $msgt; ?>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">Edit User Group Application</h6>
                <a href="group_application_list.php"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $errmsg; ?>
            </span>
            <br>

            <form class="my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?nric=" . $nric; ?>" method="POST">
                <span>Note: After Update to Accept/Reject, you can't change it back.</span><br>
                <span>If Accept, the user will automatically changed to the requested group.</span>
                <p></p>

                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" disabled>
                    <label for="username">Requester Username:</label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" id="requestgroup" name="requestgroup" placeholder="Requested Group" disabled>
                    <label for="requestgroup">Requested Group:</label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" id="requestdate" value="<?php echo $app_date; ?>" name="requestdate" placeholder="Requested Date" disabled>
                    <label for="requestdate">Requested Date: </label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" id="appacceptby" name="appacceptby" placeholder="Application Accepted By" disabled>
                    <label for="appacceptby">Application Accepted By</label>
                </div>

                <div class="form-floating">
                    <select class="form-control" id="appstatus" name="appstatus" required>
                        <?php

                        $appstatuslist = array("P", "A", "R");

                        foreach ($appstatuslist as $appstatuslists) {
                            if ($appstatuslists == "P") {
                                $appname = "Pending";
                            } else if ($appstatuslists == "A") {
                                $appname = "Accept";
                            } else if ($appstatuslists == "R") {
                                $appname = "Reject";
                            }

                            if ($app_status == "P") {
                                if ($appstatuslists == $app_status) {
                                    echo "<option value='$appstatuslists' selected>$appname</option>";
                                } else {
                                    echo "<option value='$appstatuslists'>$appname</option>";
                                }
                            } else {
                                if ($appstatuslists == $app_status) {
                                    echo "<option value='$appstatuslists' selected>$appname</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <label for="appstatus">Application Status</label>
                </div>

                <div class="d-flex justify-content-between">
                    <strong class="text-primary"></strong>
                    <span>
                        <input type="submit" name="updateUserApp" value="Update" class="btn btn-primary" id="updateUserApp">
                    </span>

                </div>
            </form>

            <form class="border-bottom" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $vp_id; ?>" method="POST" id="deleteForm" onsubmit="return confirm('Are you sure you want to delete the group application?');">
                <div class="d-flex justify-content-between">
                    <strong class="text-primary"></strong>
                    <span>
                        <input type="submit" name="deleteUserApp" value="Delete" class="btn btn-danger" id="deleteUserApp">
                    </span>
                </div>
            </form>
            <div class="border-bottom my-3"></div>
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
    <script type="text/javascript">
        let username = <?php echo json_encode($app_user_name) ?>;
        document.getElementById("username").value = username;

        let requestgroup = <?php echo json_encode($gname) ?>;
        document.getElementById("requestgroup").value = requestgroup;

        let appacceptby = <?php echo json_encode($app_accept_by) ?>;
        document.getElementById("appacceptby").value = appacceptby;
    </script>
    <script src="js/offcanvas.js"></script>
</body>

</html>