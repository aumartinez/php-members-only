<?php

session_start();

require_once("inc/dbkey.php");
require_once("inc/validation.php");

function  randomStr($length) {
  return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", 0, $length));
}

?>
