<?php

class User {
  public $id;
  public $email;
  public $firstName;
  public $lastName;
  public $address;
  public $city;
  public $state;
  public $zip;
  public $phone;
  public $phoneType;
  public $isLoggedIn = false;
  public $errorType = "fatal";
  
  function __construct() {
    if (session_id() == "") {
      session_start();
    }
    
    if (isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true) {
      $this->initUser();
    }
  }//End __construct
  
  public function authenticate($username, $pass) {
    $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conx) {
      error_log("MySQL connect error: ".mysqli_connect_error());
      return false;
    }
    
    $user = mysqli_real_escape_string($conx, $username);
    $password = mysqli_real_escape_string($conx, $pass);
    
    $sql = "SELECT *
            FROM customer
            WHERE email = '{$user}'";
    
    $query = mysqli_query($conx, $sql);
    if (!$query) {
      error_log("Cannot reach the account for {$user}");
      return false;
    }
    
    $result = mysqli_fetch_assoc($query);
    $salt = $result["salt"];
    $crypted = crypt($password, $salt);
    $crypted = substr($crypted,strlen($salt));
    $crypted = mysqli_real_escape_string($conx, $crypted);
    
    if ($crypted != $result["password"]) {
      error_log("Password for {$username} don't match");
      mysqli_free_result($query);
      mysqli_close($conx);
      return false;
    }
    
    $this->id = $result["id"];
    $this->email = $result["email"];
    $this->firstName = $result["first_name"];
    $this->lastName = $result["last_name"];
    $this->address = $result["street"];
    $this->city = $result["city"];
    $this->state = $result["state"];
    $this->phone = $result["phone"];
    $this->phoneType = $result["phone_type"];
    $this->isLoggedIn = true;
    
    $this->setSession();
    
    mysqli_free_result($query);
    mysqli_close($conx);
    
    return true;
  }// End authenticate
  
  private function setSession() {
    if (session_id() == "") {
      session_start();
    }
    
    $_SESSION["id"] = $this->id;
    $_SESSION["email"] = $this->email;
    $_SESSION["firstName"] = $this->firstName;
    $_SESSION["lastName"] = $this->lastName;
    $_SESSION["address"] = $this->address;
    $_SESSION["city"] = $this->city;
    $_SESSION["zip"] = $this->zip;
    $_SESSION["state"] = $this->state;
    $_SESSION["phone"] = $this->phone;
    $_SESSION["phoneType"] = $this->phoneType;
    $_SESSION["isLoggedIn"] = $this->isLoggedIn;
    
  }//End _setSession
  
  private function initUser() {
    if (session_id() == "") {
      session_start();
    }
    
    $this->id = $_SESSION["id"];
    $this->email = $_SESSION["email"];
    $this->firstName = $_SESSION["firstName"];
    $this->lastName = $_SESSION["lastName"];
    $this->address = $_SESSION["address"];
    $this->city = $_SESSION["city"];
    $this->zip = $_SESSION["zip"];
    $this->state = $_SESSION["state"];
    $this->phone = $_SESSION["phone"];
    $this->phoneType = $_SESSION["phoneType"];
    $this->isLoggedIn = $_SESSION["isLoggedIn"];
    
  }//End initUser
  
  public function notLoggedIn() {
    $this->isLoggedIn = false;
    $_SESSION["isLoggedIn"] = false;
  }
  
  public function logout() {
    $this->isLoggedIn = false;
    
    if (session_id() == "") {
      session_start();
    }
    
    $_SESSION["isLoggedIn"] = false;
    
    foreach($_SESSION as $key => $value) {      
      $_SESSION[$key] = "";
      unset($_SESSION[$key]);
    }
    
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
      $cookieParameters = session_get_cookie_params();
      setcookie(session_name(), "", time() - 28800, $cookieParameters["path"], $cookieParameters["domain"], $cookieParameters["secure"], $cookieParameters["httponly"]);
    }
    
    session_destroy();
  }//End logout
    
  public function emailPass($username) {
    $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conx) {
      error_log("MySQL connect error: ".mysqli_connect_error());
      return false;
    }
    
    $user = mysqli_real_escape_string($conx, $username);
    $sql = "SELECT id, email
            FROM customer
            WHERE email = '{$user}'";
            
    $query = mysqli_query($conx, $sql);
    
    if (!$query) {
      $_SESSION["error"][] = "MySQL error: ".mysqli_errno();
      mysqli_close($conx);
      return false;
    }
    
    $rows = mysqli_num_rows($query);
    
    if ($rows == 0) {
      $_SESSION["error"][] = "User with {$user} email not found.";
      mysqli_free_result($query);
      mysqli_close($conx);
      return false;
    }
    
    $result = mysqli_fetch_assoc($query);
    $id = $result["id"];
    
    mysqli_free_result($query);
    
    $hash = uniqid("", TRUE);
    $hash = md5($hash);
    $hash = mysqli_real_escape_string($conx, $hash);
    
    $sql = "INSERT INTO resetPassword (
              email_id,
              pass_key,
              create_date,
              status
            )
            VALUES (
              '{$id}',
              '{$hash}',
              NOW(),
              'A'
            )
          ";
    
    $insert = mysqli_query($conx, $sql);
    
    if (!$insert) {
      error_log("Couldn't insert data for ".$id);
      $_SESSION["error"][] = "Couldn't insert data for ".$id;      
      mysqli_close($conx);
      return false;
    }
    
    $urlHash = urlencode($hash);
    
    $serverUrl = isset($_SERVER["HTTPS"]) ? "https://" : "http://";
    $serverUrl .= $_SERVER["SERVER_NAME"];
    
    $str = $_SERVER["PHP_SELF"];
    $arr = explode("/", $str);
    $str = array("");
    
    for ($i = 0; $i < count($arr); $i++) {
      if ($i == (count($arr) - 1)) {
        break;
      }
      array_push($str, $arr[$i]);      
    }
    
    $str = join("/", $str);
    $str = substr($str, 1);
    
    $serverUrl .= $str; //Script path
    $serverUrl .= "/reset.php?user=" . $urlHash;
    
    $emailbody = '
      <!doctype html>
      <html>
        <head>
          <title>Email alert</title>
        </head>
        <body>
          <div style="font-family: Arial, sans-serif; margin: 60px auto; width: 600px">
            <h3 style="text-align: center">
              Please click on the link to reset your password
            </h3>
            
            <hr />
            
            <p>
              Click <a href="'.$serverUrl.'">here</a> to open link to reset password page.
            </p>
          </div>
        </body>
      </html>
    ';
    
    $to = $result["email"];
    $subject = "Password reset requested";
    $txt = $emailbody;
    $headers = array(
                "MIME-Version: 1.0",
                "Content-type:text/html;charset=UTF-8",
                "From: no-reply@company.com",
                "Reply-To: no-reply@company.com",
                "X-Mailer: PHP/".PHP_VERSION
                );
                
    $headers = implode("\r\n", $headers);
    $sendemail = mail($to, $subject, $txt, $headers);
    
    return true;
  }//End emailPass
  
  public function validateReset($data) {
    $password = $data["password"];
    $verify = $data["verify"];
    $hash = $data["hash"];
    
    if ($password != $verify) {
      $this->errorType = "nonfatal";
      $_SESSION["error"][] = "Passwords don't match";
      return false;
    }
    
    $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conx) {
      $_SESSION["error"][] = "Couldn't process request.";
      error_log("MySQL connect error: ".mysqli_connect_error());
      return false;
    }
    
    $decoded = urldecode($hash);
    $email = mysqli_real_escape_string($conx, $data["email"]);
    $hash = mysqli_real_escape_string($conx, $decoded);
    
    $sql = "SELECT c.id AS id, c.email AS email, c.salt AS salt
            FROM customer AS c, resetPassword AS r
            WHERE r.status = 'A' 
            AND r.pass_key = '{$hash}'
            AND c.email = '{$email}'
            AND c.id = r.email_id";
    
   $query = mysqli_query($conx, $sql);
   
   if (!$query) {
     $_SESSION["error"][] = "Error: ".mysqli_errno();
     error_log("Database error: ".$data["email"]." - ".$data["hash"]);
     mysqli_free_result($query);
     mysqli_close($conx);
     return false;
   }
   else if (mysqli_num_rows($query) == 0) {
     $_SESSION["error"][] = "Link not active or user not found.";
     $this->errorType = "fatal";
     error_log("Link not active: ".$data["email"]." - ".$data["hash"]);
     return false;
   }
   else {
     $result = mysqli_fetch_assoc($query);
     $id = $result["id"];
     $salt = $result["salt"];
     $password = mysqli_real_escape_string($conx, $password);
     
     if ($this->resetPass($id, $password, $salt, $hash)) {
       return true;
     }
     else {
       $this->errorType = "nonfatal";
       $_SESSION["error"][] = "Error resetting the password.";
       error_log("Error resetting the password: ".$id);
       return false;
     }
   }
    
  }//End validate reset
    
  private function resetPass($id, $password, $salt, $hash) {
  
    $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conx) {
      error_log("MySQL connect error: ".mysqli_connect_error());
      return false;
    }
        
    $crypted = crypt($password, $salt);
    $crypted = substr($crypted,strlen($salt));
    $crypted = mysqli_real_escape_string($conx, $crypted);
    $id = mysqli_real_escape_string($conx, $id);
        
    $sql = "UPDATE customer 
            SET password = '{$crypted}'
            WHERE id = '{$id}'";
    
    $update = mysqli_query($conx, $sql);
    
    if (!$update) {
      mysqli_close($conx);
      return false;
    }
    
    $sql = "UPDATE resetPassword
            SET status = 'U'
            WHERE pass_key = '{$hash}'";
    
    $update = mysqli_query($conx, $sql);
    
    if (!$update) {
      mysqli_close($conx);
      return false;
    }
    
    return true;
  }
  
  
}//End class User

?>
