<?php
session_start(); # to ensure PHP knows which session to work with (based on the session ID) , and calling the data that will be destroyed
session_unset(); # to destroy the session data
session_destroy(); # to destroy the session at all 
header("Location: login.html"); // Redirect to login page
exit;
?>
