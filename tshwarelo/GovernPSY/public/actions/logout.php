<?php
session_start();
session_unset();
session_destroy();

// Use ../ to jump out of the actions folder back to the main login or home page
header("Location: ../login.php"); 
exit();
?>