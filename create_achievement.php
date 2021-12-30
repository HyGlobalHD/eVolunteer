<?php
// look at achievement details
// get post by suggestion id
// hence the page will be like view_achievement.php?id=EXAMPLE

// use $_GET['parametername'];

include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';
include 'src/achievement.php';

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
} else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
}

if($_SESSION['groupcode'] !== "ADM") {
    header("location: homepage.php?msgt=2&msg=You are not allowed to access this page.");
    exit;
}

$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();
$aAPI = new achievement();

$currentUserId = $_SESSION['nric'];
$currUsergroupcode = $_SESSION['groupcode'];
// note: to check currentuser if same user as the create user

// ACHIEVEMENT_ID	ACHIEVEMENT_NAME	ACHIEVEMENT_DESCRIPTION	ACHIEVEMENT_CREATED_DATE USER_NRIC

$achievementdata = "";
//echo $achievement_id;
$achievementsectionMSG = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['createachievement'])) {
    $achievementid = $_POST['achievementid'];
    $achievementname = $_POST['achievementname'];
    $achievementdesc = $_POST['achievementdesc'];
    if (!(is_null($achievementname)) && strlen(trim($achievementname)) > 0 && !(is_null($achievementdesc)) && strlen(trim($achievementdesc)) > 0 && !(is_null($achievementid)) && strlen(trim($achievementid)) > 0) {
        //$tmpsid = $sAPI->createachievement($achievementdesc, $achievementname, $currentUserId);
        if ($aAPI->createAchievement($achievementid, $achievementname, $achievementdesc, $currentUserId)) {
            //$achievementsectionMSG = "<span class='text-success'>Successful create achievement!!</span>";
            //$achievementsectionMSG = "<script type='text/javascript'>alert('Successful create achievement!!');window.location.href = 'view_achievement.php?id=$tmpsid';</script>";
            header("location: view_achievement.php?msgt=1&msg=Successful create achievement!!");
            exit;
        } else {
            $achievementsectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
        }
    } else {
        $achievementsectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again. Please make sure the input is there.");
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
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">achievement</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="top_achievement.php">Top achievement</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="recent_achievement.php">Recent achievement</a></li>
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
                <small>Achievement</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">Create Achievement</h6>
                <a href="view_achievement.php?id=<?php echo $sID; ?>"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $achievementsectionMSG; ?>
            </span>
            <br>

            <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="achievementidID" name="achievementid" placeholder="your achievement id" autocomplete="off" maxlength="15" required>
                    <label for="achievementidID">Achievement ID</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="achievementnameID" name="achievementname" placeholder="your achievement name" autocomplete="off" maxlength="50" required>
                    <label for="achievementnameID">Achievement Name</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="achievementdescID" name="achievementdesc" placeholder="your achievement desc" autocomplete="off" maxlength="150" required>
                    <label for="achievementdescID">Achievement Description</label>
                </div>
                <input name="tmpsid" type="hidden" value="<?php echo $achievement_id; ?>">
                <div class="d-flex justify-content-between">
                    <strong class="text-primary"></strong>
                    <span>
                        <input type="submit" name="createachievement" value="create" class="btn btn-primary" id="achievementBtn">
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

    <script src="js/offcanvas.js"></script>
</body>

</html>