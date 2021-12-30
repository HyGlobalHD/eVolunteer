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
$sid = $_GET['sid'];
if(!$sAPI->checkSuggestionsExist($sid)) {
    header("location: homepage.php?msgt=0&msg=There is no suggestions post with the selected data.");
    exit;
}

// note: to check currentuser if same user as the create user
$msgt = "";

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
            header("location: view_volunteers.php?id=$tmpsid&msgt=1&msg=Successful create volunteers program!!");
            exit;
        } else {
            $volunteerssectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
        }
    } else {
        $volunteerssectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again. Please make sure yout post is there.");
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

$msgt = $msgt . $sAPI->msgbox(0, "Please be inform that you can only choose volunteer program date after 1 week or 7 days of current date.");
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
                <small>volunteers</small>
            </div>
        </div>

        <?php echo $msgt; ?>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">New volunteers program</h6>
                <a href="view_volunteers.php?id=<?php echo $sID; ?>"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $volunteerssectionMSG; ?>
            </span>
            <br>

            <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?sid=". $sid; ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="vp_titleID" name="vp_title" placeholder="your volunteers title" autocomplete="off" maxlength="50" required>
                    <label for="vp_titleID">Your volunteers title...</label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control form-outline rounded-0" id="volunteers_detailsID" name="vp_details" placeholder="your volunteers" autocomplete="off" rows="30" cols="80" tabindex="3" data-type="CHAR" aria-invalid="false" style="height: 100%;" required></textarea>
                    <label for="volunteersID">Your volunteers...</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" id="vp_start_date" name="vp_start_date" placeholder="volunteers start date" autocomplete="off" min="<?php $date = strtotime("+7 day", strtotime(date("Y-m-d"))); echo date("Y-m-d", $date); ?>" required>
                    <label for="vp_start_date">volunteers start date</label>
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control" id="vp_end_date" name="vp_end_date" placeholder="volunteers end date" autocomplete="off" min="<?php $date = strtotime("+7 day", strtotime(date("Y-m-d"))); echo date("Y-m-d", $date); ?>" required>
                    <label for="vp_end_date">volunteers end date</label>
                </div>
                <div class="form-floating">
                    <input type="number" class="form-control" id="vp_min_volunteers" name="vp_min_volunteers" placeholder="volunteers minimum participant" autocomplete="off" required>
                    <label for="vp_min_volunteers">volunteers minimum participant</label>
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