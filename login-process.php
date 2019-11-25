<?php

require_once("inc/functions.php");

if (!isset($_POST["submitForm"])) {
  header("Location: login.php");
  exit();
}

$_SESSION["submitForm"] = true;


if (isset($_SESSION["error"])) {  
  unset($_SESSION["error"]);
}

$_SESSION["error"] = array();

$required = array(
            "email",
            "password"
            );

//Check required
foreach ($required as $value) {
  if (!isset($_POST[$value]) || $_POST[$value] == "") {
    $_SESSION["error"][] = $value." is required";
  }
}

//Validate email address
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  $_SESSION["error"][] = "Email address is invalid.";
}

if (count($_SESSION["error"]) > 0) {
  header("Location: login.php");
  exit();
}
else {
  $user = new User();
  
  if ($user->authenticate($_POST["email"], $_POST["password"])) {
    unset($_SESSION["submitForm"]);
    header("Location: index.php");
    exit();
  }
  else {    
    $_SESSION["error"][] = "User couldn't be authenticated, try again.";
    header("Location: login.php");
    exit();
  }
}

?>
