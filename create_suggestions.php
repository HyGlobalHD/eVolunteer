<?php
// look at suggestions details
// get post by suggestion id
// hence the page will be like view_suggestions.php?id=EXAMPLE

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

// note: to check currentuser if same user as the create user

$suggestionsdata = "";
//echo $suggestions_id;
$suggestionssectionMSG = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&  isset($_POST['createSuggestions'])) {
  $suggestionsgiven = $_POST['suggestions'];
  $suggestionstitlegiven = $_POST['suggestionstitle'];
  if (!(is_null($suggestionsgiven)) && strlen(trim($suggestionsgiven)) > 0 && !(is_null($suggestionstitlegiven)) && strlen(trim($suggestionstitlegiven)) > 0) {
    $tmpsid = $sAPI->createSuggestions($suggestionstitlegiven, $suggestionsgiven, $currentUserId);
    if (!(is_null($tmpsid))) {
      //$suggestionssectionMSG = "<span class='text-success'>Successful create suggestions!!</span>";
      //$suggestionssectionMSG = "<script type='text/javascript'>alert('Successful create suggestions!!');window.location.href = 'view_suggestions.php?id=$tmpsid';</script>";
      header("location: view_suggestions.php?id=$tmpsid&msgt=1&msg=Successful create suggestions!!");
      exit;
    } else {
      $suggestionssectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again.");
    }
  } else {
    $suggestionssectionMSG = $sAPI->msgbox(3, "Opsie! Something wrong happen! Try again. Please make sure the input is there.");
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel='icon' href='favicon.png' type='image/png' />
  <title>eVolunteer - New Suggestions</title>

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
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-success active" href="#">Create Suggestions</a></li>
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
              <li><a class="dropdown-item" href="view_achievement.php">Achievement</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="group_apply.php">Group Application</a></li>
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
        <h6 class="pb-2 mb-0">New Suggestions</h6>
      </div>
      <span>
        <?php echo $suggestionssectionMSG; ?>
      </span>
      <br>

      <form class="border-bottom my-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-floating">
          <input type="text" class="form-control" id="suggestionstitleID" name="suggestionstitle" placeholder="your suggestions title" autocomplete="off" maxlength="50" required>
          <label for="suggestionstitleID">Your suggestions title...</label>
        </div>
        <div class="form-floating">
          <textarea class="form-control form-outline rounded-0" id="suggestionsID" name="suggestions" placeholder="your suggestions" autocomplete="off" rows="30" cols="80" onclick="checkLen(this.value)" onkeypress="checkLen(this.value)" onkeyup="checkLen(this.value)" tabindex="3" data-type="CHAR" aria-invalid="false" style="height: 100%;" required></textarea>
          <label for="suggestionsID">Your suggestions...<span id="counterDisplay"></span></label>
        </div>
        <input name="tmpsid" type="hidden" value="<?php echo $suggestions_id; ?>">
        <div class="d-flex justify-content-between">
          <strong class="text-primary"></strong>
          <span>
            <input type="submit" name="createSuggestions" value="create" class="btn btn-primary" id="suggestionsBtn" disabled>
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
    function checkLen(val) {
      if (val.length > 0) {
        document.getElementById('counterDisplay').innerHTML = '(' + val.length + ' / 280)';
        document.getElementById('suggestionsBtn').disabled = false;
      } else {
        document.getElementById('counterDisplay').innerHTML = '';
        document.getElementById('suggestionsBtn').disabled = true;
      }
    }
  </script>

  <script src="js/offcanvas.js"></script>
</body>

</html>