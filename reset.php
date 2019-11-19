<?php

require_once("inc/functions.php");

$invalidAccess = true;

if (isset($_GET["user"]) && $_GET["user"] != "") {
  $invalidAccess = false;
  $hash = $_GET["user"];
}

//If password reset submit form failed
if (isset($_SESSION["submitForm"]) && $_SESSION["submitForm"] == true) {
  $invalidAccess = false;
  $hash = $_SESSION["hash"];
}

if ($invalidAccess) {
  header("Location: login.php");
  exit();
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Reset Password</title>
    
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="css/global.css" rel="stylesheet" type="text/css" />
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-center wrapper">
          <form id="resetForm" action="reset-process.php" method="post">
            <fieldset>
              <legend>
                Reset Password
              </legend>
              
              <div id="errorDiv">
                <?php
                  if (isset($_SESSION["error"]) && isset($_SESSION["formSubmit"])) {
                    unset($_SESSION["formSubmit"]);
                    echo "\n";
                    echo "Errors found.";
                    echo "<br />\n";
                    
                    foreach ($_SESSION["error"] as $error) {
                      echo $error."<br />\n";
                    }
                  }//End if
                ?>
              </div>
              
              <div class="form-group">
                <p>
                  <strong><span class="required">*</span> are required</strong>
                </p>
              </div>
              
              <div class="form-group">
                <label for="email">
                  E-mail Address: <span class="required">*</span>
                </label>
                <input class="form-control" type="email" id="email" name="email" />
                <p class="errorMess">
                  E-mail is required
                </p>
              </div>
              
              <div class="form-group">
                <label for="password">
                  Password: <span class="required">*</span>
                </label>
                <input class="form-control" type="password" id="password" name="password" />
                <p class="errorMess">
                  Password is required
                </p>
                <p>
                  <small>min 6 characters length</small>
                </p>                
              </div>
              
              <div class="form-group">
                <label for="verify">
                  Verify Password: <span class="required">*</span>
                </label>
                <input class="form-control" type="password" id="verify" name="verify" />
                <p class="errorMess">
                  Passwords don't match
                </p>
                <p>
                  <small>min 6 characters length</small>
                </p>
              </div>
              
              <div class="form-group">
                <?php
                  echo "\n";
                  echo "<input type=\"hidden\" name=\"hash\" value=\"{$hash}\" />\n";
                ?>
              </div>
              
              <div class="form-group">
                <button class="btn btn-primary" type="submit" id="submitForm" name="submitForm">
                  Submit
                </button>
              </div>
              
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/reset.js"></script>
  </body>
</html>
