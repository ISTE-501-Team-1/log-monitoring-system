<?php

class checkauth {
    
    // Call this static funciton using checkauth::isAuthenticated
    public static function isAuthenticated() {

        // Are they logged in?
        //if ( isset($_COOKIE['loggedIn']) ) {
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {

            // Yes, they are! :D
            return true;
        
        }
        
        // No, they aren't :(
        return false;

    } // Ends isAuthenticated function

} // Ends checkauth class

?>