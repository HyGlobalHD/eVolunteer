<?php
// look at suggestions details
// get post by suggestion id
// hence the page will be like view_suggestions.php?id=EXAMPLE

// use $_GET['parametername'];

include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';
include 'src/group.php';

session_start();


$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();
$gAPI = new group();

$suggestions_id = $_GET['id'];
$currentUserId = $_SESSION['nric'];
$groupcode = $_SESSION['groupcode'];

// check whether user already login
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // nric // groupcode
} else {
    header("location: user_login.php?msgt=2&msg=Please login first.");
    exit;
}

if(!$sAPI->checkSuggestionsExist($suggestions_id)) {
    header("location: homepage.php?msgt=0&msg=There is no suggestions post with the selected data.");
    exit;
}

$content = $contentTitle = $contentCreator = $dateCreated = $contentVote = $commentdata = $checkEditable = $votebtnmsg = $votebtn = $vpbtn = "";
//echo $suggestions_id;
$commentsectionMSG = "";

$msgt = "";
if (isset($_GET['msgt']) && isset($_GET['msg'])) {
    $msgt = $msgt . $sAPI->msgbox($_GET['msgt'], $_GET['msg']);
    // get the message type based on the numeric value
}

if($sAPI->checkVP($suggestions_id)) {
    $vp_id = $sAPI->checkVPID($suggestions_id);
    $msgt = $msgt . $sAPI->msgbox(0, "This suggestions has been picked for volunteer program please go to <a href='view_volunteers.php?id=".$vp_id."'>this link for more info.</a>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['votebtn'])) {
    if ($sAPI->createUserVote($currentUserId, $suggestions_id)) {
        $votebtnmsg = $sAPI->msgbox(1, "Successfully voted!");
    } else {
        $votebtnmsg = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unvotebtn'])) {
    if ($sAPI->deleteUserVote($currentUserId, $suggestions_id)) {
        $votebtnmsg = $sAPI->msgbox(1, "Successfully unvoted!");
    } else {
        $votebtnmsg = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['vpbtn'])) {
    // TODO redirect to create_volunteer.php?sid=$suggestions_id
    header("location: create_volunteer.php?sid=$suggestions_id");
    exit;
}

$detail = $sAPI->getSuggestionsDetails($suggestions_id);
if (!(is_null($detail))) {
    foreach ($detail as $details) {
        $sTitle = $details['SUGGESTIONS_TITLE'];
        $sDetails = $details['SUGGESTIONS_DETAILS'];
        $sCreatedDate = $details['SUGGESTIONS_CREATED_DATE'];
        $cCreatedBy = $details['USER_NRIC'];
        $userUsername = $uAPI->getUserUsername($cCreatedBy);
        $voteCount = $sAPI->getVote($suggestions_id);

        $dateCreated = $sCreatedDate;
        $contentTitle = $sTitle;
        $contentCreator = $userUsername;
        $contentVote = $voteCount;
        $content = str_replace(["\r\n", "\r", "\n"], '\n', $sDetails);

        if ($currentUserId == $cCreatedBy || $groupcode == "ADM") {
            $checkEditable = "<div class='d-flex justify-content-between'><strong class='text-primary'></strong><span> <a href='edit_suggestions.php?id=$suggestions_id' class='primary-text' style='text-decoration: none;'>Edit Suggestions... </a></span></div>";
        }
    }
} else {
    // todo: show non-exist etc...
}

$commentlimiter = 5; // limit user comment per post // great way to reduce spam

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['commentSent'])) {
    $commentgiven = $_POST['comment'];
    if (!(is_null($commentgiven)) && strlen(trim($commentgiven)) > 0) {
        if ($sAPI->getUserCommentCount($suggestions_id, $currentUserId) == $commentlimiter) {
            $commentsectionMSG = $sAPI->msgbox(0, "You have reached the limit comment for this post");
        } else {
            if ($sAPI->postSuggestionComment($suggestions_id, $currentUserId, $commentgiven)) {
                $commentsectionMSG = $sAPI->msgbox(1, "Successfully comment");
            } else {
                $commentsectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
            }
        }
    } else {
        $commentsectionMSG = $sAPI->msgbox(2, "Please make sure your comment is there");
    }
}

$pagesOption = ""; // pages numbering

// settings:
$offset = 0; //note: offset must start with 0
$limit = 10; // can be changed // default: 10
$offsetSettings = 10; // can be change // default:10 // use for increase offset per pages

$totalPages = ceil($sAPI->getCommentRecordCount() / $offsetSettings); // eg: 2 / 10 = 0.2 = 1 pages total

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentPage'])) {
    $currPage = $_POST['pages']; // must be numbering
    for ($i = 1; $i < intval($currPage); $i++) { // i = 0; i < 1; i++ = $offset = 0 + 10;
        $offset = $offset + $offsetSettings;
    }
    $commentdata = getComment($suggestions_id, $offset, $limit);
    // display results
} else {
    // default
    $currPage = 1;
    $commentdata = getComment($suggestions_id, $offset, $limit);
}

$pagesOption = getPages($currPage, $totalPages);

//$pagesOption = getPages($currPage, $totalPages);
function getPages($currPage, $totalPages)
{ // return string
    $result = "";
    if ($totalPages < 1) {
        $result = $result . "<option value='1' selected>1</option>";
    } else {
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == intval($currPage)) {
                $result = $result . "<option value='$i' selected>$i</option>";
            } else {
                $result = $result . "<option value='$i'>$i</option>";
            }
        }
    }
    return $result;
}

function getComment($suggestions_id, $offsets, $limits)
{
    $commentdata = "";
    $dbAPI = new db();
    $sAPI = new suggestions();
    $uAPI = new users();
    $gAPI = new group();
    $currentUserId = $_SESSION['nric'];

    $commentdetail = $sAPI->getSuggestionsCommentLimitOrder($suggestions_id, $offsets, $limits, 'DESC');
    if (is_null($commentdetail)) {
        $commentdata = "There is no available comment...";
    } else {
        foreach ($commentdetail as $commentdetails) {
            $sc_id = $commentdetails['SC_ID'];
            $sId = $commentdetails['SUGGESTIONS_ID'];
            $cComment = $commentdetails['COMMENT'];
            $cDateTime = $commentdetails['COMMENT_DATE_TIME'];
            $cCreatedBy = $commentdetails['USER_NRIC'];
            $userUsername = $uAPI->getUserUsername($cCreatedBy);

            $usergroupcode = $uAPI->getUserGroupCode($cCreatedBy);
            $groupcode = $gAPI->getGroupName($usergroupcode);

            if ($cCreatedBy == $currentUserId) {
                $commentdata = $commentdata . "<div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><span class='badge rounded-pill  bg-primary'>" . $groupcode . "</span><div class='d-flex justify-content-between'><strong class='text-primary  position-relative'><a href='user_profile.php?username=$userUsername' style='text-decoration: none;'>@" . $userUsername . "</a></strong><span>" . $cDateTime . "</span></div><div class='d-flex justify-content-between'><span class='text-muted'>" . $cComment . "</span><span><a class='text-primary' style='text-decoration: none;' href='suggestions_comment.php?sc_id=$sc_id&sid=$sId'>Change</a></span></div></div></div>";
                /*
                <form class='border-bottom my-3' action='<?php echo htmlspecialchars(".$_SERVER['PHP_SELF'].") . '?id=' . $suggestions_id; ?>'
method='POST'>
<div class='form-floating'>
    <input type='text' class='form-control' id='commentID' name='comment' placeholder='your comment' autocomplete='off'
        maxlength='280' onkeypress='checkLen(this.value)' onkeyup='checkLen(this.value)' required>
    <label for='commentID'>Your comment...<span id='counterDisplay'></span></label>
    <div class='d-flex justify-content-between'>
        <strong class='text-primary'></strong>
        <input type='submit' name='commentSent' value='comment' class='btn btn-primary' id='commentBtn' disabled>
    </div>
</div>
</form>"; */
} else {
$commentdata = $commentdata . "<div class='d-flex text-muted pt-3'><svg
        class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg'
        role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'>
        <title>Placeholder</title>
        <rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text>
    </svg>
    <div class='pb-3 mb-0 small lh-sm border-bottom w-100'><span class='badge rounded-pill  bg-primary'>" . $groupcode .
            "</span>
        <div class='d-flex justify-content-between'><strong class='text-primary'><a
                    href='user_profile.php?username=$userUsername' style='text-decoration: none;'>@" . $userUsername .
                    "</a></strong><span>" . $cDateTime . "</span></div><span class='d-block text-muted'>" . $cComment .
            "</span>
    </div>
</div>";
}
}
}
return $commentdata;
}

if ($sAPI->getUserVote($currentUserId, $suggestions_id)) {
$votebtn = "<input type='submit' class='btn btn-success' value='unvote' name='unvotebtn'>";
} else {
$votebtn = "<input type='submit' class='btn btn-success' value='vote' name='votebtn'>";
}

if(($groupcode == "ORG" || $groupcode == "ADM") && $sAPI->checkVP($suggestions_id) === false){
$vpbtn = "<input type='submit' class='btn btn-success' value='choose as volunteer program' name='vpbtn'>";
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='icon' href='favicon.png' type='image/png' />
    <title>eVolunteer - Suggestions</title>

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
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown01" data-bs-toggle="dropdown"
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
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
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

    <style>
    .container img {
        width: 40%;
        height: 40%;
    }
    </style>

    <main class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white bg-dark rounded shadow-sm">
            <div class="lh-1">
                <h1 class="h6 mb-0 text-white lh-1"><span style="color: #7289DA;">e</span>Volunteer</h1>
                <small>Suggestions</small>
            </div>
        </div>

        <?php echo $msgt; ?>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h1 class="pb-2 mb-0 text-decoration-underline"><?php echo $contentTitle; ?></h1>
            <div class="border-bottom" id="contentmarkdown"></div>
            <script src="js/marked.min.js"></script>
            <script>
            let content = '<?php echo $content; ?>'.replace(/\\n/g, '\n');
            document.getElementById('contentmarkdown').innerHTML = marked.parse(content.replace(/\\n/g, '\n'));
            </script>

            <div class="d-flex justify-content-between text-muted">
                <strong class="text-primary"></strong>
                <span>Suggest by: <a class="text-primary" style="text-decoration: none;"
                        href="user_profile.php?username=<?php echo $contentCreator; ?>">@<?php echo $contentCreator; ?></a></span>
            </div>
            <div class="d-flex justify-content-between text-muted">
                <span class="text-muted">Like the suggestions?? Give a vote!!</span>
                <span>Suggestion create on: <?php echo $dateCreated; ?></span>
            </div>
            <div class="d-flex justify-content-between text-muted">
                <span class="text-primary">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $suggestions_id; ?>"
                        method="POST">
                        <?php echo $votebtnmsg; ?>
                        <?php echo $votebtn; ?>
                        <?php echo $vpbtn; ?>
                    </form>
                </span>
                <span> Vote: <?php echo $contentVote; ?></span>
            </div>
            <?php echo $checkEditable; ?>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Comment</h6>
            <?php echo $commentsectionMSG; ?>
            <form class="border-bottom my-3"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $suggestions_id; ?>" method="POST">
                <div class="form-floating">
                    <input type="text" class="form-control" id="commentID" name="comment" placeholder="your comment"
                        autocomplete="off" maxlength="280" onkeypress="checkLen(this.value)"
                        onkeyup="checkLen(this.value)" required>
                    <label for="commentID">Your comment...<span id="counterDisplay"></span></label>
                    <div class="d-flex justify-content-between">
                        <strong class="text-primary"></strong>
                        <input type="submit" name="commentSent" value="comment" class="btn btn-primary" id="commentBtn"
                            disabled>
                    </div>
                </div>
            </form>
            <div class="border-bottom my-3"></div>
            <?php echo $commentdata;
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $suggestions_id; ?>"
                method="POST">
                <label for="pages">Comment Pages:</label>
                <select name="pages" id="pages">
                    <?php echo $pagesOption; ?>
                </select>
                <input type="submit" class="btn-primary" name="commentPage" value="Go">
            </form>
            <small class="d-block text-end mt-3">
                <a href="#"></a>
            </small>
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
    </script>
    <script src="js/offcanvas.js"></script>
</body>

</html>