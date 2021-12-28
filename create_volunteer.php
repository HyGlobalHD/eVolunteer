<?php
// look at volunteers details
// get post by volunteer id
// hence the page will be like view_volunteers.php?id=EXAMPLE

// use $_GET['parametername'];

include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
} else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
}
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

$currentUserId = $_SESSION['nric'];

if(!$sAPI->checkSuggestionsExist($suggestions_id)) {
    header("location: homepage.php?msgt=0&msg=There is no suggestions post with the selected data.");
    exit;
}

// note: to check currentuser if same user as the create user

$volunteersdata = "";
//echo $volunteers_id;
$volunteerssectionMSG = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['createVolunteerProgram'])) {
    $vp_title = $_POST['vp_title'];
    $vp_details = $_POST['vp_details'];
    $vp_start_date = $_POST['vp_start_date'];
    $vp_end_date = $_POST['vp_end_date'];
    $vp_min_volunteers = $_POST['vp_min_volunteers'];
    if (!(is_null($vp_details)) && strlen(trim($vp_details)) > 0 && !(is_null($vp_title)) && strlen(trim($vp_title)) > 0) {
        $tmpsid = $sAPI->createVolunteerProgram($vp_title, $vp_details, $vp_start_date, $vp_end_date, $vp_min_volunteers, $currentUserId, $sid);
        if (!(is_null($tmpsid))) {
            //$volunteerssectionMSG = "<span class='text-success'>Successful create volunteers!!</span>";
            //$volunteerssectionMSG = "<script type='text/javascript'>alert('Successful create volunteers!!');window.location.href = 'view_volunteers.php?id=$tmpsid';</script>";
            header("location: view_volunteers.php?id=$tmpsid&msgt=1&msg=Successful create volunteers!!");
            exit;
        } else {
            $volunteerssectionMSG = "<span class='text-danger'>Opsie! Something wrong happen! Try again.</span>";
        }
    } else {
        $volunteerssectionMSG = "<span class='text-info'>Please make sure your volunteers title is there</span>";
    }
}

$detail = $sAPI->getSuggestionsDetails($sid);
if(!(is_null($detail))) {
    foreach ($detail as $details) {
        $sID = $details['SUGGESTIONS_ID'];
        $sDetails = $details['SUGGESTIONS_DETAILS'];
        $sCreatedDate = $details['SUGGESTIONS_CREATED_DATE'];
        $cCreatedBy = $details['USER_NRIC'];
        $userUsername = $uAPI->getUserUsername($cCreatedBy);
    
        $suggestionsdatav2 = array('suggestionsdetails' => $sDetails);
        $suggestionstitlev2 = array('suggestionstitle' => $details['SUGGESTIONS_TITLE']);
        $suggestionsdata = $sDetails;
    }
}
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
                        <a class="nav-link" aria-current="page" href="homepage.php">Homepage</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">volunteers</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="top_volunteers.php">Top volunteers</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="recent_volunteers.php">Recent volunteers</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="volunteer_program.php">Volunteer Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Participation Status</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
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


    <main class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white bg-dark rounded shadow-sm">
            <div class="lh-1">
                <h1 class="h6 mb-0 text-white lh-1"><span style="color: #7289DA;">e</span>Volunteer</h1>
                <small>volunteers</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">New volunteers program</h6>
                <a href="view_volunteers.php?id=<?php echo $sID; ?>"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $volunteerssectionMSG; ?>
            </span>
            <br>

            <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="vp_titleID" name="vp_title" placeholder="your volunteers title" autocomplete="off" maxlength="50" required>
                    <label for="vp_titleID">Your volunteers title...</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control form-outline rounded-0" id="volunteers_detailsID" name="vp_details" placeholder="your volunteers" autocomplete="off" rows="30" cols="80" tabindex="3" data-type="CHAR" aria-invalid="false" style="height: 100%;" required></textarea>
                    <label for="volunteersID">Your volunteers...</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" id="vp_start_date" name="vp_start_date" placeholder="volunteers start date" autocomplete="off" required>
                    <label for="vp_start_date">volunteers start date</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" id="vp_end_date" name="vp_end_date" placeholder="volunteers end date" autocomplete="off" required>
                    <label for="vp_end_date">volunteers end date</label>
                </div>
                <div class="form-floating">
                    <input type="number" class="form-control" id="vp_min_volunteer" name="vp_min_volunteer" placeholder="volunteers minimum participant" autocomplete="off" required>
                    <label for="vp_min_volunteer">volunteers minimum participant</label>
                </div>
                <input name="tmpsid" type="hidden" value="<?php echo $sid; ?>">
                <div class="d-flex justify-content-between">
                    <strong class="text-primary"></strong>
                    <span>
                        <input type="submit" name="createVolunteerProgram" value="create" class="btn btn-primary" id="volunteersBtn">
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
        let sdtv2 = <?php echo json_encode($suggestionstitlev2); ?>;
        document.getElementById('vp_titleID').value = sdtv2.suggestionstitle;
        let sdv2 = <?php echo json_encode($suggestionsdatav2); ?>;
        document.getElementById('volunteers_detailsID').value = sdv2.suggestionsdetails;
    </script>
    <script src="js/offcanvas.js"></script>
</body>

</html>