<?php
// look at Volunteer details
// get post by suggestion id
// hence the page will be like view_Volunteer.php?id=EXAMPLE

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

$currentUserId = $_SESSION['nric'];

$searchMSG = $queryResult = "";
$username = "";
if(isset($_GET['username']) && strlen(trim($_GET['username'])) > 0){
    $username = $_GET['username'];
    $queryResult = searchQuery($username);
}

$msgt = "";
if (isset($_GET['msgt']) && isset($_GET['msg'])) {
  $msgt = $sAPI->msgbox($_GET['msgt'], $_GET['msg']);
  // get the message type based on the numeric value
}
// note: to check currentuser if same user as the create user

$Volunteerdata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['searchVolunteer'])) {
    $search = $_POST['search'];
    if(!(is_null($search)) && strlen(trim($search)) > 0){
        $queryResult = searchQuery($search);
        if(!(is_null($queryResult))){
            $msgt = $sAPI->msgbox(1, "Successful search volunteer program!!");
        }else{
            $msgt = $sAPI->msgbox(0, "There is no volunteer program with the search input. Please try another input.");
        }
    } else {
        $msgt = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again. Please make sure the input is there.");
    }
}

function searchQuery($search) {
    $dbAPI = new db();
    $sAPI = new suggestions();
    $uAPI = new users();
    $queryResult = "";
    if (!(is_null($search)) && strlen(trim($search)) > 0) {
        $squery = $sAPI->searchVolunteerProgram($search);

        if (is_null($squery)) {
            $queryResult = $queryResult . "There is no result for your search";
        } else {
            foreach ($squery as $details) {
                $sId = $details['VP_ID'];
                $sTitle = $details['VP_TITLE'];
                $sDetails = $details['VP_DETAILS'];
                $sCreatedDate = $details['VP_PICKED_DATE'];
                $cCreatedBy = $details['USER_NRIC'];
                $userUsername = $uAPI->getUserUsername($cCreatedBy);
                $voteCount = $sAPI->getVote($sId);

                $queryResult = $queryResult . "<a href='view_Volunteer.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
            }
            return $queryResult;
        }
    } else {
        return $queryResult;
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
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Volunteer</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="top_Volunteer.php">Top Volunteer</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="recent_Volunteer.php">Recent Volunteer</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="VP_program.php">Volunteer Program</a>
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
                <small>Volunteer</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">Search Volunteer</h6>
                <a href="homepage.php"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $msgt; ?>
            </span>
            <br>

            <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="searchID" name="search" placeholder="your search" autocomplete="off" required>
                    <label for="searchID">Your search...</label>
                </div>
                <div class="d-flex justify-content-between">
                    <strong class="text-primary"></strong>
                    <span>
                        <input type="submit" name="searchVolunteer" value="search" class="btn btn-primary" id="searchBtn">
                    </span>
                </div>
            </form>
            <div class="border-bottom my-3"></div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Results:</h6>
            <?php echo $queryResult; ?>
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