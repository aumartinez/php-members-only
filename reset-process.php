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
            "password",
            "verify"
            );

//Check required
foreach ($required as $value) {
  if (!(isset($_POST[$value])) || $_POST[$value] == "") {
    $_SESSION["error"][] = $value." is required";
  }
}

//Validate email address
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  $_SESSION["error"][] = "Email address is invalid.";
}

//Check passwords match
if (isset($_POST["password"]) && isset($_POST["verify"])) {
  if ($_POST["password"] != $_POST["verify"]) {
    $_SESSION["error"][] = "Passwords don't match.";
  }
}

if (count($_SESSION["error"]) > 0) {
  header("Location: reset.php");
  exit();
}
else {
  $user = new User();
  
  if ($user->validateReset($_POST)) {
    unset($_SESSION["submitForm"]);
    header("Location: reset-success.php");
    exit();
  }
  else {
    if ($user->errorType = "nonfatal") {
      $_SESSION["hash"] = $_POST["hash"];
      $_SESSION["error"][] = "There was a problem with the form.";
      header("Location: reset.php");
      exit();
    }
    else {
      $_SESSION["error"][] = "The request couldn't' be processed.";
      header("Location: reset.php");
      exit();
    }
  }
}

?>
