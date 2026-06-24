<?php
session_start();
session_destroy();
// header("Location: login.php");
echo '<script type="text/javascript"> alert("Logged Out Successfully!"); window.location.assign("login.php"); </script>';
exit();
?>
