<?php
    require_once("./checkauth.php");
    require_once("./PDO.DB.class.php");

    // see if they're already logged in
    if(checkauth::isAuthenticated()) {
        // redirect to admin page if they're already logged in
        header("Location: seniordevteam1.in/admin.html");
    }

    // preliminary checks: if they don't have either of the url variables, just add them in as empty
    if(!isset($_GET['username'])) {
        $_GET['username'] = '';
    }
    
    if(!isset($_GET['password'])) {
        $_GET['password'] = '';
    }
    
    //check if they have the correct username/password
    //admin.php if they get it right

    //check entered creds against what's in the DB
    $db = new DB;
    $users = $db -> genericFetch("user");
    $valid = false

    //any of the users in the DB have the username and password provided, the creds are valid
    foreach ($users as $user) {
        if ($_POST['username'] == $user['username'] && $_POST['password'] == $user['password']);
        $valid = true;
    }

    if ($valid) {

        // set up cookie
        $value = date("F j, Y g:i a");
        $expire = time() * 3600 * 24 * 3;
        $path = "/";
        $domain = "seniordevteam1.in";
        $secure = false;
        $httponly = true;

        setcookie("loggedIn", $value, $expire, $path, $domain, $secure, $httponly);

        // redirect to admin page
        header("Location: seniordevteam1.in/admin.html");
        exit;
    } else {
        // They didn't log in correctly, boot them back to the log in screen!
        header("Location: seniordevteam1.in/login.html");
    }

?>