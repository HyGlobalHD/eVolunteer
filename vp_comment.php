<?php
// look at suggestions details
// get post by suggestion id
// hence the page will be like view_suggestions.php?id=EXAMPLE

// use $_GET['parametername'];

include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
  }else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
  }
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

$vc_id = $_GET['vc_id'];
$vp_id = $_GET['sid'];
$currentUserId = $_SESSION['nric'];

// note: to check currentuser if same user as the create user

$commentdata = "";
//echo $vp_id;
$commentsectionMSG = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateComment'])) {
    $commentgiven = $_POST['comment'];
    if (!(is_null($commentgiven)) && strlen(trim($commentgiven)) > 0) {
        if ($sAPI->updateVolunteerComment($vc_id, $commentgiven)) {
            $commentsectionMSG = $sAPI->msgbox(1, "Successful update comment!!");
        } else {
            $commentsectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
        }
    } else {
        $commentsectionMSG = $sAPI->msgbox(2, "Please make sure your comment is there");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['deleteComment'])) {
    $tmpsid = $_POST['tmpsid'];
    if ($sAPI->deleteVolunteerComment($vc_id)) {
        //$commentsectionMSG = "<script type='text/javascript'>alert('Delete Successful!');window.location.href = 'view_suggestions.php?id=$tmpsid';</script>";
        header('Location: view_volunteers.php?id=' . $tmpsid . '&msgt=1&msg=Delete the comment Successful!');
        exit;
    } else {
        $commentsectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
    }
}

$detail = $sAPI->getVolunteerComment($vc_id);
if (!(is_null($detail))) {
    foreach ($detail as $details) {
        $sID = $details['VP_ID'];
        $sDetails = $details['COMMENT'];
        $sCreatedDate = $details['COMMENT_DATE_TIME'];
        $cCreatedBy = $details['USER_NRIC'];
        $userUsername = $uAPI->getUserUsername($cCreatedBy);

        $suggestionsdatav2 = array('suggestionsdetails' => $sDetails);
        $commentdata = $sDetails;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='favicon.png' type='image/png' />
    <title>eVolunteer - Volunteer Program Comment</title>

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
            <span class="navbar-brand" style="pointer-events: none;cursor: default;"><span
                    style="color: #7289DA;">e</span>Volunteer</span>
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="homepage.php">Homepage</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                            aria-expanded="false">Suggestions</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="top_suggestions.php">Top Suggestions</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="recent_suggestions.php">Recent Suggestions</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-success" href="create_suggestions.php">Create
                                    Suggestions</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="search_suggestions.php">Search Suggestions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown"
                            aria-expanded="false">Volunteer Program</a>
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
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                            aria-expanded="false">Settings</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="view_achievement.php">Achievement</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="group_apply.php">Group Application</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item dropdown-item-danger d-flex gap-2 align-items-center"
                                    href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

    <main class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white bg-dark rounded shadow-sm">
            <div class="lh-1">
                <h1 class="h6 mb-0 text-white lh-1"><span style="color: #7289DA;">e</span>Volunteer</h1>
                <small>Volunteer Program</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">Comment</h6>
                <a href="view_volunteers.php?id=<?php echo $vp_id; ?>"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $commentsectionMSG; ?>
            </span>
            <br>
            <form class="border-bottom my-3"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?vc_id=" . $vc_id . "&sid=" . $vp_id; ?>"
                method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="commentID" name="comment" placeholder="your comment"
                        autocomplete="off" maxlength="280" onclick="checkLen(this.value)"
                        onkeypress="checkLen(this.value)" onkeyup="checkLen(this.value)">
                    <input name="tmpsid" type="hidden" value="<?php echo $vp_id; ?>">
                    <label for="commentID">Your comment...<span id="counterDisplay"></span></label>
                    <div class="d-flex justify-content-between">
                        <strong class="text-primary"></strong>
                        <span>
                            <input type="submit" name="updateComment" value="update" class="btn btn-primary"
                                id="commentBtn" disabled>
                            <input type="submit" name="deleteComment" value="delete" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete the comment?')">
                        </span>
                    </div>
                </div>
            </form>
            <div class="border-bottom my-3"></div>
        </div>

        <div class="text-center">
            <p class="mt-5 mb-3 text-muted" style="color: #7289DA;">e<span style="color: #2C2F33;">Volunteer &copy;
                    2021</span></p>
        </div>
    </main>

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-muted">&copy; 2021 eVolunteer</p>
        </footer>
    </div>
    <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
    function checkLen(val) {
        if (val.length > 0) {
            document.getElementById('counterDisplay').innerHTML = '(' + val.length + ' / 280)';
            document.getElementById('commentBtn').disabled = false;
        } else {
            document.getElementById('counterDisplay').innerHTML = '';
            document.getElementById('commentBtn').disabled = true;
        }
    }

    let sdv2 = <?php echo json_encode($suggestionsdatav2); ?>;
    document.getElementById('commentID').value = sdv2.suggestionsdetails;
    </script>
    <script src="js/offcanvas.js"></script>
</body>

</html>