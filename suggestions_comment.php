<?php
// look at suggestions details
// get post by suggestion id
// hence the page will be like view_suggestions.php?id=EXAMPLE

// use $_GET['parametername'];

include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

session_start();

$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

$sc_id = $_GET['sc_id'];
$suggestions_id = $_GET['sid'];
$currentUserId = $_SESSION['nric'];

// note: to check currentuser if same user as the create user

$commentdata = "";
//echo $suggestions_id;
$commentsectionMSG = "";



if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['updateComment'])) {
    $commentgiven = $_POST['comment'];
    if (!(is_null($commentgiven)) && strlen(trim($commentgiven)) > 0) {
        if ($sAPI->updateSuggestionComment($sc_id, $commentgiven)) {
            $commentsectionMSG = "<span class='text-success'>Successful update comment!!</span>";
        } else {
            $commentsectionMSG = "<span class='text-danger'>Opsie! Something wrong happen! Try again.</span>";
        }
    } else {
        $commentsectionMSG = "<span class='text-info'>Please make sure your comment is there</span>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['deleteComment'])) {
    $tmpsid = $_POST['tmpsid'];
    if ($sAPI->deleteSuggestionComment($sc_id)) {
        $commentsectionMSG = "<script type='text/javascript'>alert('Delete Successful!');window.location.href = 'view_suggestions.php?id=$tmpsid';</script>";
    } else {
        $commentsectionMSG = "<span class='text-danger'>Opsie! Something wrong happen! Try again.</span>";
    }
}

$detail = $sAPI->getSuggestionsCommentDetails($sc_id);
if(!(is_null($detail))) {
    foreach ($detail as $details) {
        $sID = $details['SUGGESTIONS_ID'];
        $sDetails = $details['COMMENT'];
        $sCreatedDate = $details['COMMENT_DATE_TIME'];
        $cCreatedBy = $details['USER_NRIC'];
        $userUsername = $uAPI->getUserUsername($cCreatedBy);
    
        $commentdata = $sDetails;
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
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Suggestions</a>
                        <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="top_suggestions.php">Top Suggestions</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="recent_suggestions.php">Recent Suggestions</a></li>
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
                <small>Suggestions</small>
            </div>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="d-flex justify-content-between border-bottom">
                <h6 class="pb-2 mb-0">Comment</h6>
                <a href="view_suggestions.php?id=<?php echo $sID; ?>"><button class="btn btn-success">Back</button></a>
            </div>
            <span>
                <?php echo $commentsectionMSG; ?>
            </span>
            <br>

            <span> Original comment:
                <?php echo $commentdata; ?>
            </span>
            <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?sc_id=" . $sc_id; ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="commentID" name="comment" placeholder="your comment" autocomplete="off" maxlength="280" onclick="checkLen(this.value)" onkeypress="checkLen(this.value)" onkeyup="checkLen(this.value)">
                    <input name="tmpsid" type="hidden" value="<?php echo $suggestions_id; ?>">
                    <label for="commentID">Your comment...<span id="counterDisplay"></span></label>
                    <div class="d-flex justify-content-between">
                        <strong class="text-primary"></strong>
                        <span>
                            <input type="submit" name="updateComment" value="update" class="btn btn-primary" id="commentBtn" disabled>
                            <input type="submit" name="deleteComment" value="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the comment?')">
                        </span>
                    </div>
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
        function checkLen(val) {
            if (val.length > 0) {
                document.getElementById('counterDisplay').innerHTML = '(' + val.length + ' / 280)';
                document.getElementById('commentBtn').disabled = false;
            } else {
                document.getElementById('counterDisplay').innerHTML = '';
                document.getElementById('commentBtn').disabled = true;
            }
        }
    </script>
    <script src="js/offcanvas.js"></script>
</body>

</html>