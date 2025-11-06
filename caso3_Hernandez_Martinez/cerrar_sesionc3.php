<?php
session_start();
session_unset();
session_destroy();
header("Location: loginc3.php");
exit();
?>
