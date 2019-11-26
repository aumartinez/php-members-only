<?php
require_once("inc/functions.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Password Recovery</title>
    
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
          <form id="emailForm" action="email-process.php" method="post">
            <fieldset>
              <legend>
                Password Recovey
              </legend>
              
              <div id="errorDiv">
                <?php
                  if (isset($_SESSION["error"]) && isset($_SESSION["submitForm"])) {
                    unset($_SESSION["submitForm"]);
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
                <label for="email">
                  Email address: <span class="required">*</span>
                </label>
                <input class="form-control" type="text" id="email" name="email" />
                <p class="errorMess">
                  Email is required
                </p>
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
    <script src="js/recovery.js"></script>
  </body>
</html>
