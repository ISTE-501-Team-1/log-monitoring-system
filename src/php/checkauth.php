<?php

class checkauth {
    // you can call this static funciton like checkauth::isAuthenticated
    public static function isAuthenticated() {
        // are they logged in?
        if (isset($_COOKIE['loggedIn'])) {
            // yes, they are! :D
            return true;
        }
        // no, they aren't :(
        return false;
    }
  }

// don't close php tag, as this is an included file