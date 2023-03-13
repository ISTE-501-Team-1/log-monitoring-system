<?php

//require_once "models/PDO.DB.class.php";
require_once "views/common_ui.php";
view_common_includes("");

if (!isset($_SESSION['loggedIn'])) {
    
    view_login_main();

} elseif ($_SESSION['loggedIn']) {

    // Redirect to dashboard
    header("Location: https://seniordevteam1.in/views/dashboard_ui.php");
    exit;
    // view_common_header();
    // view_common_navigation();
    // view_dashboard_main();
    // view_common_footer();
    
} // Ends if

?>