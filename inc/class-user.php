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
}//End class User

?>
