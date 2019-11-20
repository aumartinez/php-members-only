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
    
    $regUser = mysqli_real_escape_string($conx, $username);
    $password = mysqli_real_escape_string($conx, $pass);
    
    $sql = "SELECT *
            FROM customer
            WHERE email = '{$regUser}'";
    
    $query = mysqli_query($conx, $sql);
    if (!$query) {
      error_log("Cannot reach the account for {$username}");
      return false;
    }
    
    $result = mysqli_fetch_assoc($query);
    $salt = $result["salt"];
    $crypted = crypt($password, $salt);
    $crypted = substr($crypted,strlen($salt));
    
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
    
    if (ini_get("seesion.use_cookies")) {
      $cookieParameters = session_get_cookie_params();
      setcookie(session_name(), "", time() - 28800, $cookieParameters["path"], $cookieParameters["domain"], $cookieParameters["secure"], $cookieParameters["httponly"]);
    }
    
    session_destroy();
  }
  
  public function emailPass($username) {
    $conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if (!$conx) {
      error_log("MySQL connect error: ".mysqli_connect_error());
      return false;
    }
    
    $user = mysqli_real_escape_string($username);
    $sql = "SELECT id, email
            FROM customer
            WHERE email = '{$user}'";
            
    $query = mysqli_query($conx, $sql);
    
    if (!$query) {
      $_SESSION["error"][] = "MySQL error: ".mysqli_errno();
      mysqli_close($conx);
      return false;
    }
    
    $result = mysqli_fetch_row($query);
    
    if ($result == 0) {
      $_SESSION["error"][] = "User with {$user} email not found."
      mysqli_free_result($query);
      mysqli_close($conx);
      return false;
    }
    
    $result = mysqli_fetch_assoc($query);
    $id = $result["id"];
    
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
      $_SESSION["error"][] = "MySQL error: ".mysqli_errno();
      mysqli_close($conx);
      return false;
    }
    
    $urlHash = urlencode($hash);
    
    $serverUrl = isset($_SERVER["HTTPS"]) ? "https://" : "http://";
    $serverUrl .= $_SERVER["SEVER_NAME"];
    
    $str = $_SERVER["PHP_SELF"];
    $arr = explode("/", $str);
    $str = array();
    
    for ($i = 0; $i < count($arr); $i++) {
      if ($i == (count($arr) - 1)) {
        break;
      }
      array_push($str, $arr[$i]);      
    }
    
    $str = join("/", $str);
    $str = substr($str, 1);
    
    $serverUrl .= $str; //Script path
    $serverUrl .= "/reset.php";
    
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
    $subject = "Passwrod reset requested";
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
  }//End emailPass
  
  
}//End class User

?>
