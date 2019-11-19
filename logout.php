<?php

require_once("inc/functions.php");
$user = new User();
$user->logout();
header("Location: login.php");
exit();

?>
