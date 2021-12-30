<?php

session_start();

$_SESSION = array();

session_destroy();

header("location: index.php?msgt=1&msg=You have been logged out.");
exit;

?>