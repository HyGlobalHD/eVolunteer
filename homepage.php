<?php

// homepage -> show top suggestions and recent suggestion I guess ???
// top 3 suggestions
// below that
// recent suggestions ??? I guess
include 'src/db.php';
include 'src/suggestions.php';
include 'src/users.php';

session_start();

$top3 = "";
$recent3 = "";
$dbAPI = new db();
$sAPI = new suggestions();
$uAPI = new users();

// show top 3 suggestions
$detail = $sAPI->getTopSuggestionsLimitOrder(0, 3, 'DESC');
if(is_null($detail)) {
        $top3 = $top3 . "There is no top suggestions available for now.";
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
    
          $top3 = $top3 . "<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>".$sTitle."</strong><span>Vote: ". $voteCount ."</span></div><span class='d-block text-muted'>@".$userUsername."</span><span>". $sCreatedDate ."</span></div></div></a>";
        }
      }
    }
  


// show 5 recent suggestions
$detail = $sAPI->getRecentSuggestionsLimitOrder(0, 3, 'DESC');
if(is_null($detail)) {
    $recent3 = $recent3 . "There is no recent suggestions available for now.";
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
    
      $recent3 = $recent3."<a href='view_suggestions.php?id=" . $sId . "' class='text-muted' style='text-decoration: none;'><div class='d-flex text-muted pt-3'><svg class='bd-placeholder-img flex-shrink-0 me-2 rounded' width='32' height='32' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Placeholder: 32x32' preserveAspectRatio='xMidYMid slice' focusable='false'><title>Placeholder</title><rect width='100%' height='100%' fill='#007bff' /><text x='50%' y='50%' fill='#007bff' dy='.3em'>32x32</text></svg><div class='pb-3 mb-0 small lh-sm border-bottom w-100'><div class='d-flex justify-content-between'><strong class='text-primary'>".$sTitle."</strong><span>Vote: ". $voteCount ."</span></div><span class='d-block text-muted'>@".$userUsername."</span><span>". $sCreatedDate ."</span></div></div></a>";
    }
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
            <a class="nav-link active" aria-current="page" href="#">Homepage</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Suggestions</a>
            <ul class="dropdown-menu mx-0 shadow" aria-labelledby="dropdown01">
              <li><a class="dropdown-item" href="top_suggestions.php">Top Suggestions</a></li>
              <li><hr class="dropdown-divider"></li>
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
              <li><hr class="dropdown-divider"></li>
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
        <small>Homepage</small>
      </div>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Top 3 Suggestions</h6>
      <?php echo $top3; ?>
      <small class="d-block text-end mt-3">
        <a href="top_suggestions.php">See more top suggestions</a>
      </small>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
      <h6 class="border-bottom pb-2 mb-0">Recent Suggestions</h6>
      <?php echo $recent3; ?>
      <small class="d-block text-end mt-3">
        <a href="recent_suggestions.php">See more recent suggestions</a>
      </small>
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