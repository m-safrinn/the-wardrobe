<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION['logout'] = "Logout successful. Come back again !!!";
header("Location: home.php");
exit();
