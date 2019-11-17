<?php

session_start();

require_once("inc/dbkey.php");
require_once("inc/validation.php");

function randomStr($length) {  
  return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

?>
