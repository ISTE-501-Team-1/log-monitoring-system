<?php

require_once "../views/common_ui.php";
require_once "../controllers/validation_controller.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Search Student", false, 2);

if (isset($_GET['recent'])) {
    view_student_list_recent();
} elseif (isset($_GET['setFilters'])) {
    action_student_list_set_filters();
} elseif (isset($_GET['clearFilters'])) {
    action_student_list_clear_filters();
} else {
    view_student_list_main();
} // Ends if

view_common_footer();

function view_student_list_main() {

    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    if (isset($_GET["student"])) {

        $filterByUsername = $filterByClass = $filterByLastName = $sortBy = "";

        if (isset($_GET["sortBy"])) {
            $sortBy = $_GET["sortBy"];
        }

        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //     if ($_POST["studentSearchUsername"]) {
        //         $filterByUsername = sanitize_string($_POST["studentSearchUsername"]);
        //     }

        //     if ($_POST["studentSearchLastName"]) {
        //         $filterByLastName = sanitize_string($_POST["studentSearchLastName"]);
        //     }

        //     if ($_POST["studentSearchClass"]) {
        //         $filterByClass = sanitize_string($_POST["studentSearchClass"]);
        //     }
            
        // } // Ends if

        if (isset($_COOKIE["studentSearchUsernameCookie"])) {
            $filterByUsername = $_COOKIE["studentSearchUsernameCookie"];
        }

        if (isset($COOKIE["studentSearchLastNameCookie"])) {
            $filterByLastName = $_COOKIE["studentSearchLastNameCookie"];
        }

        if (isset($_COOKIE["studentSearchClassCookie"])) {
            $filterByClass = $_COOKIE["studentSearchClassCookie"];
        }

        $studentObjects = $db->getStudentObjectsByRoleFilteredAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage, $sortBy, $filterByUsername, $filterByClass, $filterByLastName);
        $totalRows = $db->getStudentObjectsByRoleFilteredCount($currentUser[0], $currentUser[6], $sortBy, $filterByUsername, $filterByClass, $filterByLastName);
    
    } else {
        
        $studentObjects = $db->getStudentObjectsByRoleAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);
        $totalRows = $db->getStudentObjectsByRoleCount($currentUser[0], $currentUser[6]);
    
    }// Ends if

    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);

    $classArray = $db->getClassArray($currentUser[0], $currentUser[6]);

    view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage);
    view_student_list_filter_modal($classArray);

} // Ends view_student_list_main()

function view_student_list_recent() {
    
    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    $totalRows = $db->getActivityStudentObjectsCount($currentUser[0]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the student objects for the current page
    $studentObjects = $db->getActivityStudentObjectsAsTable($currentUser[0], $currentPage, $recordsPerPage);

    $classArray = $db->getClassArray($currentUser[0], $currentUser[6]);

    view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage);
    view_student_list_filter_modal($classArray);

} // Ends view_student_list_recent

function view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage) {

    if (isset($_GET["sortBy"])) {

        $sortBy = $_GET["sortBy"];
        $checkedID = $checkedUsername = $checkedSchool = "\"";

        switch ($sortBy) {

            case "id":
                $checkedID = "checked";
                break;
            case "username":
                $checkedUsername = "checked";
                break;
            case "school":
                $checkedSchool = "checked";
                break;
            case "lastName":
                $checkedLastName = "checked";
                break;

        } // Ends switch
        
    } // Ends if

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <div class="container pt-4 d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-4">

                <p class="h3 m-auto">Students</p>

                <!-- Sort By Filter -->
                <div class="dropdown">

                    <a class="btn btn-outline-dark btn-rounded" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-sort-amount-down-alt fa-lg" ></i>
                            <p class="lh-1 fs-6 m-auto">Sort By</p>
                        </div>
                    </a>

                    <ul class="dropdown-menu sort-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="StudentID" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=id\'" '.$checkedID.' />
                                <label class="form-check-label" for="MostRecent"> ID </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Username" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=username\'" '.$checkedUsername.' />
                                <label class="form-check-label" for="Username"> Username </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="School" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=school\'" '.$checkedSchool.' />
                                <label class="form-check-label" for="School"> School </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="LastName" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=lastName\'" '.$checkedLastName.' />
                                <label class="form-check-label" for="LastName"> Last Name </label>
                            </div>
                        </li>
                    </ul>

                </div>

                <a class="btn btn-outline-dark btn-rounded ripple-surface" type="button" cursor: pointer; data-mdb-toggle="modal" data-mdb-target="#studentModal">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-filter" ></i>
                        <p class="lh-1 fs-6 m-auto">Filter</p>
                    </div>
                </a>
    ');

    // Displays chips for each filter that is added
    if (isset($_COOKIE["studentSearchGeneralCookie"])) {

        if (isset($_COOKIE["studentSearchUsernameCookie"]) && !empty($_COOKIE["studentSearchUsernameCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Username: '.$_COOKIE["studentSearchUsernameCookie"].'
                </div>
            ');

        } // Ends if

        if (isset($_COOKIE["studentSearchLastNameCookie"]) && !empty($_COOKIE["studentSearchLastNameCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Last Name: '.$_COOKIE["studentSearchLastNameCookie"].'
                </div>
            ');

        } // Ends if

        if (isset($_COOKIE["studentSearchClassCookie"]) && !empty($_COOKIE["studentSearchClassCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Class: '.$_COOKIE["studentSearchClassCookie"].'
                </div>
            ');

        } // Ends if

        if (isset($_GET['recent'])) {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php\'">
                    Clear Filters
                    <span class="closebtn">&times;</span>
                </div>
            ');

        } else {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?clearFilters\'">
                    Clear Filters
                    <span class="closebtn">&times;</span>
                </div>
            ');

        }

    } // Ends if

    echo('
            </div>

            <!-- Pagination links -->
            <nav aria-label="Pagination">
                <ul class="pagination pagination-circle pagination-custom">
                    <li class="page-item pagination-plain-text">
                        <p class="lh-1 fs-6">Page</p>
                    </li>
                    '.get_common_pagination($totalNumberOfPages, $currentPage).'
                </ul>
            </nav>

        </div>

        <!-- Student Table -->
        <div class="container pt-2 long-table-container">
            <div class="table-responsive search-table">

                <table id="studentsListTable" class="table table-hover">
                    '.$studentObjects.'
                </table>

            </div>
        </div>

    </main>
    ');

} // Ends view_student_list_table

function view_student_list_filter_modal($classArray) {

    echo '
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="https://seniordevteam1.in/views/student_list_ui.php?setFilters" method="post">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Student Search Filters</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                            <label for="studentSearchUsername">Username:</label>
                            <input name="studentSearchUsername" type="search" id="studentSearchBar" placeholder="Username">
                            
                            <br />

                            <label for="studentSearchLastName">Last Name:</label>
                            <input name="studentSearchLastName" type="search" id="studentSearchBar" placeholder="Last Name">
                            
                            <br />

                            <!--Class Dropdown Filter-->
                            <label for="studentSearchClass" style="padding-top:1em">Class:</label>
                            <select name="studentSearchClass" id="studentSearchClass" style="width: 200px; overflow-wrap: break-word; word-wrap: break-word;">
    ';

    $classDropdown = "";
    foreach ($classArray as $class) {
        $classID = $class['classId'];
        $className = $class['className'];
        $classDropdown .= '<option value="' . $classID . '">' . $className . '</option>';
    }

    echo $classDropdown;
                                
    echo '
                            </select>

                            <br />

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark btn-rounded" data-mdb-ripple-color="dark" data-mdb-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-rounded btn-primary">Apply Filter</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    ';

} // Ends view_student_list_filter_modal

function action_student_list_set_filters() {

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

} // Ends action_student_list_set_filters

function action_student_list_clear_filters() {

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

} // Ends action_student_list_clear_filters

?>