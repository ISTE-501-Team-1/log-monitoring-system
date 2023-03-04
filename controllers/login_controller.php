<?php
    require_once("authentication_controller.php");
    require_once("../models/PDO.DB.class.php");
    $db = new DB();

    // Call function to see if user has already logged in, and redirects to dashboard if true
    if(checkauth::isAuthenticated()) {
        header("Location: https://seniordevteam1.in/frontend_test/mainDashboard.html");
        exit;
    }

    // TODO: replace with error checking 
    // Preliminary checks: if they don't have either of the url variables, just add them in as empty
    if(!isset($_POST['userUsername'])) {
        $_POST['username'] = '';
    }
    
    if(!isset($_POST['userPassword'])) {
        $_POST['password'] = '';
    }

    // Calls function to get a record for a user by username and password
    // If a valid record exists, and array is returned. If not, then a string is returned
    $loggedInUser = $db->getUserInfoByLogin($_POST['userUsername'], $_POST['userPassword']);

    if ( is_array($loggedInUser) ) {

        // Set timezone
        date_default_timezone_set('EST');

        // Create session
        session_name('loginSession');
        session_start();

        // Set cookie params
        $value = date("F j, Y g:i a");
        $expire = time() + (60*180); // Expires in 3 hours
        $path = "/";
        $domain = "seniordevteam1.in";
        $secure = false;
        //$httponly = true;

        // Set session variable
        $_SESSION['loggedIn'] = true;

        // Set cookies to hold ID and classification of user returned in array from login function
        setcookie("loggedInBool", true, $expire, $path, $domain, $secure);
        setcookie("loggedInUserID", $loggedInUser[0], $expire, $path, $domain, $secure);
        setcookie("loggedInUserClassification", $loggedInUser[1], $expire, $path, $domain, $secure);
        //setcookie("loggedIn", $value, $expire, $path, $domain, $secure, $httponly);

        // Redirect to dashboard
        header("Location: https://seniordevteam1.in/frontend_test/mainDashboard.html");
        exit;

    } else {

        // They didn't log in correctly, boot them back to the log in screen!
        header("Location: https://seniordevteam1.in");
        exit;

    } // Ends login if

?>