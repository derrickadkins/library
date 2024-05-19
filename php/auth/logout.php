<?php
/*
 * This script is used to log out a user from the library system.
 * 
 * It starts a session, unsets all session variables, and destroys the session.
 * This effectively logs out the user by removing all their session data.
 * 
 * The script then redirects the user to the index page and terminates the script 
 * execution.
 */

session_start();
session_unset();
session_destroy();

header("Location: ../../index.php");
exit();
?>