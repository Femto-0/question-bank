<?php
// logout.php
session_start();
session_destroy();
header("Location: login.php"); //takes the user back to the log in page
exit();
?>
