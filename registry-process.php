<?php

require_once("inc/functions.php");

if (!isset($_POST["submitForm"])) {
  header("Location: register.php");
  exit();
}

$_SESSION["form"] = true;

if (isset($_SESSION["error"])) {
  unset($_SESSION["error"]);
}

$_SESSION["error"] = array();

$required = array(
              "firstName",
              "lastName",
              "email",
              "password",
              "verify"
            );

//Check required
foreach ($required as $value) {
  if (!(isset($_POST[$value]) || $_POST[$value] == "")) {
    $_SESSION["error"][] = $value." is required";
  }
}

//Validate firstName and lastName
if (!preg_match('/^[\w .]+$/', $_POST["firstName"])) {
  $_SESSION["error"][] = "First Name must be letter and numbers only.";
}

if (!preg_match("/^[\w .]+$/", $_POST["lastName"])) {
  $_SESSION["error"][] = "First Name must be letter and numbers only.";
}

if (isset($_POST["email"]) && $_POST["email"] != "") {
  if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"][] = "Email is invalid.";
  }
}

if (isset($_POST["password"]) && isset($_POST["verify"])) {
  if ($_POST["password"] != "" && $_POST["verify"] != "") {
    if ($_POST["password"] != $_POST["verify"]) {
      $_SESSION["error"][] = "Passwords don't match.";
    }
    else if (strlen($_POST["password"]) < 6 || strlen($_POST["verify"]) < 6) {
      $_SESSION["error"][] = "Passwords should be more than 6 characters.";
    }
  }
}

//Additional validations
if (isset($_POST["state"]) && $_POST["state"] != "") {
  if (!is_valid_state($_POST["state"])) {
    $_SESSION["error"][] = "Please choose a valid state.";
  }
}

if (isset($_POST["zip"]) && $_POST["zip"] != "") {
  if (!is_valid_zip($_POST["zip"])) {
    $_SESSION["error"][] = "ZIP code error.";
  }
}

if (isset($_POST["phone"]) && $_POST["phone"] != "") {
  if (!preg_match("/^[\d]+$/", $_POST["phone"])) {
    $_SESSION["error"][] = "Phone number should be only digits.";
  }
  else if (strlen($_POST["phone"] < 10)) {
    $_SESSION["error"][] = "Phone number must be at least 10 digits.";
  }
  
  if (!isset($_POST["phonetype"]) || $_POST["phonetype"] == "") {
    $_SESSION["error"][] = "Please choose a phone number type.";
  }
  else {
    $validPhoneTypes = array(
    "work",
    "home"
    );
    
    if (!in_array($_POST["phonetype"], $validPhoneTypes)) {
      $_SESSION["error"][] = "Please choose a valid phone number type.";
    }
  }
}

//Final errors check
if (count($_SESSION["error"]) > 0) {
  header("Location: register.php");
  exit();
}
else {
  if(registerUser($_POST)) {
    unset($_SESSION["form"]);
    header("Location: success.php");    
  }
  else {
    $messLog = "Problem registering user: {$_POST["email"]}.";
    $messDestination = $_SESSION["error"][] = "Problem registering account.";
    $messHeaders = die(header("Location: register.php"));
    error_log($messLog, $messDestination, $messHeaders);
    
  }
}


function registerUser($userData) {
  $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
  if (!$conx) {
    $messLog = "MySQL connection failed: ".mysqli_connect_errno();
    error_log($messLog);
    return false;
  }
  
  $email = mysqli_real_escape_string($conx, $_POST["email"]);
  
  $sql = "SELECT id 
          FROM customer
          WHERE email = '{$email}'";
  
  $query = mysqli_query($conx, $sql);
  if (!$query) {
    error_log(die("SQL query error: ".mysqli_error($conx)));
    return false;
  }
  
  $result = mysqli_num_rows($query);
  if ($result == 1) {
    $_SESSION["error"][] = "A user with that e-mail address already exists.";
    mysqli_close($conx);
    mysqli_free_result($query);
    return false;
  }
  
  mysqli_free_result($query);
  
  $firstName = mysqli_real_escape_string($_POST["firstName"]);
  $lastName = mysqli_real_escape_string($_POST["lastName"]);
  
  $salt = "\$6\$rounds=5000\$".randomStr(8)."\$";
  $password = mysqli_real_escape_string($_POST["password"]);
  $crypted = crypt($password, $salt);
  
  $street = isset($_POST["address"]) ? mysqli_real_escape_string($_POST["address"]) : "";
  $city = isset($_POST["city"]) ? mysqli_real_escape_string($_POST["city"]) : "";
  $state = isset($_POST["state"]) ? mysqli_real_escape_string($_POST["state"]) : "";
  $zip = isset($_POST["zip"]) ? mysqli_real_escape_string($_POST["zip"]) : "";
  $phone = isset($_POST["phone"]) ? mysqli_real_escape_string($_POST["phone"]) : "";
  $phonetype = isset($_POST["phonetype"]) ? mysqli_real_escape_string($_POST["phonetype"]) : "";
  
  $sql = "INSERT INTO customer (
          email,
          create_date,
          password,
          salt,
          first_name,
          last_name,
          street,
          city,
          state,
          zip,
          phone,
          phone_type
          )
          VALUES(
          '{$email}',
          NOW(),
          '{$crypted}',
          '{$salt}',
          '{$firstName}',
          '{$lastName}',
          '{$street}',
          '{$city}',
          '{$state}',
          '{$zip}',
          '{$phone}',
          '{$phonetype}'
          )";
  
  $query = mysqli_query($conx, $sql);
  
  if ($query) {
    $id = mysqli_insert_id($conx);
    error_log("New user with email:{$email} registered as ID {$id}.");
    mysqli_close($conx);
    return true;
  }
  else {
    error_log("Couldn't add new registry, the query failed: ".mysqli_error($conx));
    mysqli_close($conx);
    return false;
  }
}//End function

?>