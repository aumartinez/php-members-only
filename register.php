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
    <title>Registration</title>
    
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
        <div class="col-sm-4 col-center">
          <form id="userForm" action="registry-process.php" method="post">
            <fieldset>
              <legend>
                Registration Information
              </legend>
              
              <div id="errorDiv">
                <?php
                  if (isset($_SESSION["error"]) && isset($_SESSION["formSubmit"])) {
                    unset($_SESSION["formSubmit"])
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
                <label for="firstName">
                  First Name: <span class="required">*</span>
                </label>
                <input class="form-control" type="text" id="firstName" name="firstName"/>
                <p class="errorMess">
                  First Name is required
                </p>
              </div>
              
              <div class="form-group">
                <label for="lastName">
                  Last Name: <span class="required">*</span>
                </label>
                <input class="form-control" type="text" id="lastName" name="lastName" />
                <p class="errorMess">
                  Last Name is required
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
                <label for="address">
                  Street address:
                </label>
                <input class="form-control" type="text" id="address" name="address" />
              </div>
              
              <div class="form-group">
                <label for="city">
                  City:
                </label>
                <input class="form-control" type="text" id="city" name="city" />
              </div>
              
              <div class="form-group">
                <label for="state">
                  State:
                </label>
                <select class="form-control" id="state" name="state">
                  <option>--Select--</option>
                  <option value="AL">Alabama</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="FL">Florida</option>
                  <option value="IL">Illinois</option>
                  <option value="NJ">New Jersey</option>
                  <option value="NY">New York</option>
                  <option value="WI">Wisconsin</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="zip">
                  Zip:
                </label>
                <input class="form-control" type="text" id="zip" name="zip" />
                <p class="errorMess">
                  Use a valid zip code
                </p>
              </div>
              
              <div class="form-group">
                <label for="phone">
                  Phone:
                </label>
                <input class="form-control" type="text" id="phone" name="phone" />
                <p class="errorMess">
                  Format: XXX-XXX-XXXX (10 digits)
                </p>
              </div>
              
              <div class="form-group">
                <label id="phonetype">
                  Number type:
                </label>
                <div class="radio-group">
                  <input class="form-control radio-input" id="work" value="work" name="phonetype" type="radio" />
                  <label for="work" class="radio-lbl">
                    Work
                  </label>
                </div>
                <div class="radio-group">
                  <input class="form-control radio-input" id="home" value="home" name="phonetype" type="radio" />
                  <label for="home" class="radio-lbl">
                    Home
                  </label>
                </div>                
                <p class="errorMess clearer">
                  Please choose an option
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
    <script src="js/register.js"></script>
  </body>
</html>
