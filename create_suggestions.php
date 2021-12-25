<?php
include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

session_start();

// TODO CHECK SESSION

$recent = "";
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

$pagesOption = ""; // pages numbering

// settings:
$offset = 0; //note: offset must start with 0
$limit = 10; // can be changed // default: 10
$offsetSettings = 10; // can be change // default:10 // use for increase offset per pages
// how many pages
$totalPages = ceil($sAPI->getRecordCount() / $offsetSettings); // eg: 2 / 10 = 0.2 = 1 pages total

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $currPage = $_POST['pages']; // must be numbering
  for ($i = 1; $i < intval($currPage); $i++) { // i = 0; i < 1; i++ = $offset = 0 + 10;
    $offset = $offset + $offsetSettings;
  }
  // display results
  $recent = getRecentPost($offset, $limit);
} else {
  // default
  $currPage = 1;
  $recent = getRecentPost($offset, $limit);
}
$pagesOption = getPages($currPage, $totalPages);


function getPages($currPage, $totalPages)
{ // return string
  $result = "";
  for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == intval($currPage)) {
      $result = $result . "<option value='$i' selected>$i</option>";
    } else {
      $result = $result . "<option value='$i'>$i</option>";
    }
  }
  return $result;
}

function getRecentPost($offsets, $limits)
{
  $recent = "";
  $dbAPI = new db();
  $sAPI = new suggestions();
  $uAPI = new users();
  $detail = $sAPI->getRecentSuggestionsLimitOrder($offsets, $limits, 'DESC');
  if(is_null($detail)) {
      $recent = $recent . "There is no recent suggestions available for now.";
  } else {
    foreach ($detail as $details) {
      $sId = $details['SUGGESTIONS_ID'];
      if($sAPI->checkVP($sId) == false) {
        $sTitle = $details['SUGGESTIONS_TITLE'];
        $sDetails = $details['SUGGESTIONS_DETAILS'];
        $sCreatedDate = $details['SUGGESTIONS_CREATED_DATE'];
        $cCreatedBy = $details['USER_NRIC'];
        $userUsername = $uAPI->getUserUsername($cCreatedBy);
        $voteCount = $sAPI->getVote($sId);
    
        $recent = $recent . "<a href='#' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>" . $sTitle . "</strong><span>Vote: " . $voteCount . "</span></div><span class='d-block text-muted'>@" . $userUsername . "</span><span>" . $sCreatedDate . "</span></div></div></a>";
      }
    }
  }
  return $recent;
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
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item active" href="#">Recent Suggestions</a></li>
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
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item dropdown-item-danger d-flex gap-2 align-items-center" href="#">Logout</a></li>
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
        <small>Create Suggestions</small>
      </div>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Create Suggestions</h6>
      <?php echo $recent; ?>
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