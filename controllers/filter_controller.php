<?php

require_once("validation_controller.php");
require_once("authentication_controller.php");
require_once ("../views/common_ui.php");
view_common_includes("../");
$db = new DB();

if (isset($_GET["setLog"])) {

    create_log_filter_cookies();
    header("Location: https://seniordevteam1.in/views/log_list_ui.php?log");
    exit;

} elseif (isset($_GET["clearLog"])) {
    
    destroy_log_filter_cookies();
    header("Location: https://seniordevteam1.in/views/log_list_ui.php");
    exit;

} elseif (isset($_GET["setStudent"])) {

    create_student_filter_cookies();
    header("Location: https://seniordevteam1.in/views/student_list_ui.php?log");
    exit;

} elseif (isset($_GET["clearStudent"])) {

    destroy_student_filter_cookies();
    header("Location: https://seniordevteam1.in/views/student_list_ui.php");
    exit;

} // Ends if

function create_log_filter_cookies() {

    date_default_timezone_set('EST');
    $expire = time() + (60*180); // Expires in 3 hours
    $path = "/";
    $domain = "seniordevteam1.in";
    $secure = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        setcookie("logSearchGeneralCookie", true, $expire, $path, $domain, $secure);

        if (isset($_POST["logSearchUsername"])) {
            setcookie("logSearchUsernameCookie", sanitize_string($_POST["logSearchUsername"]), $expire, $path, $domain, $secure);
        }

        if (isset($_POST["logSearchType"])) {
            setcookie("logSearchTypeCookie", $_POST["logSearchType"], $expire, $path, $domain, $secure);
        }

        if (isset($_POST["logSearchTime"])) {
            setcookie("logSearchTimeCookie", $_POST["logSearchTime"], $expire, $path, $domain, $secure);
        }
        
    } // Ends if

    header("Location: https://seniordevteam1.in/views/log_list_ui.php?log");
    exit;

} // Ends create_log_filter_cookies

function destroy_log_filter_cookies() {

    unset($_COOKIE["logSearchGeneralCookie"]);
    $params = session_get_cookie_params();
    setcookie("logSearchGeneralCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["logSearchUsernameCookie"]);
    $params = session_get_cookie_params();
    setcookie("logSearchUsernameCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["logSearchTypeCookie"]);
    $params = session_get_cookie_params();
    setcookie("logSearchTypeCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["logSearchTimeCookie"]);
    $params = session_get_cookie_params();
    setcookie("logSearchTimeCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    header("Location: https://seniordevteam1.in/views/log_list_ui.php");
    exit;

} // Ends destroy_log_filter_cookies

function create_student_filter_cookies() {

    date_default_timezone_set('EST');
    $expire = time() + (60*180); // Expires in 3 hours
    $path = "/";
    $domain = "seniordevteam1.in";
    $secure = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        setcookie("studentSearchGeneralCookie", true, $expire, $path, $domain, $secure);

        if (isset($_POST["studentSearchUsername"])) {
            setcookie("studentSearchUsernameCookie", sanitize_string($_POST["studentSearchUsername"]), $expire, $path, $domain, $secure);
        }

        if (isset($_POST["studentSearchLastName"])) {
            setcookie("studentSearchLastNameCookie", sanitize_string($_POST["studentSearchLastName"]), $expire, $path, $domain, $secure);
        }

        if (isset($_POST["studentSearchClass"])) {
            setcookie("studentSearchClassCookie", $_POST["studentSearchClass"], $expire, $path, $domain, $secure);
        }
        
    } // Ends if

    header("Location: https://seniordevteam1.in/views/student_list_ui.php?log");
    exit;

} // Ends create_student_filter_cookies

function destroy_student_filter_cookies() {

    unset($_COOKIE["studentSearchGeneralCookie"]);
    $params = session_get_cookie_params();
    setcookie("studentSearchGeneralCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["studentSearchUsernameCookie"]);
    $params = session_get_cookie_params();
    setcookie("studentSearchUsernameCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["studentSearchLastNameCookie"]);
    $params = session_get_cookie_params();
    setcookie("studentSearchLastNameCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    unset($_COOKIE["studentSearchClassCookie"]);
    $params = session_get_cookie_params();
    setcookie("studentSearchClassCookie", '', 1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

    header("Location: https://seniordevteam1.in/views/student_list_ui.php");
    exit;

} // Ends destroy_student_filter_cookies