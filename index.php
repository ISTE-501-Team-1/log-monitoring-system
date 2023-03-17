<?php

//require_once "models/PDO.DB.class.php";
require_once "views/common_ui.php";
view_common_includes("");

if (!isset($_SESSION['loggedIn']) OR !isset($_COOKIE['loggedInBool'])) {
    
    view_login_main();

} elseif ($_SESSION['loggedIn']) {

    // Redirect to dashboard
    header("Location: https://seniordevteam1.in/views/dashboard_ui.php");
    exit;
    
} // Ends if

?>