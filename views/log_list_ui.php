<?php

require_once "../views/common_ui.php";
require_once "../controllers/validation_controller.php";
require_once "../controllers/filter_controller.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Search Logs", false, 1);

if (isset($_GET['recent'])) {
    view_log_list_recent();    
} else if (isset($_GET['today'])) {
    view_log_list_created_today();
} else {
    view_log_list_main();
} // Ends if

// else if (isset($_GET['setFilters'])) {
//     create_log_filter_cookies(); 
// } else if (isset($_GET['clearFilters'])) {
//     destroy_log_filter_cookies();
// } 

view_common_footer();

function view_log_list_main() {

    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    if (isset($_GET["log"])) {

        $filterByUsername = $filterByType = $filterByTime = $sortBy = "";

        if (isset($_GET["sortBy"])) {
            $sortBy = $_GET["sortBy"];
        }

        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //     if (isset($_POST["logSearchUsername"])) {
        //         $filterByUsername = sanitize_string($_POST["logSearchUsername"]);
        //     }

        //     if (isset($_POST["logSearchType"])) {
        //         $filterByType = $_POST["logSearchType"];
        //     }

        //     if (isset($_POST["logSearchTime"])) {
        //         $filterByTime = $_POST["logSearchTime"];
        //     }
            
        // } // Ends if

        if (isset($_COOKIE["logSearchUsernameCookie"])) {
            $filterByUsername = $_COOKIE["logSearchUsernameCookie"];
        }

        if (isset($COOKIE["logSearchTypeCookie"])) {
            $filterByType = $_COOKIE["logSearchTypeCookie"];
        }

        if (isset($_COOKIE["logSearchTimeCookie"])) {
            $filterByTime = $_COOKIE["logSearchTimeCookie"];
        }

        $logObjects = $db->getLogObjectsByRoleFilteredAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage, $sortBy, $filterByUsername, $filterByTime, $filterByType);
        $totalRows = $db->getLogObjectsByRoleFilteredCount($currentUser[0], $currentUser[6], $sortBy, $filterByUsername, $filterByTime, $filterByType);
    
    } else {
        
        $logObjects = $db->getLogObjectsByRoleAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);
        $totalRows = $db->getLogObjectsByRoleCount($currentUser[0], $currentUser[6]);
    
    }// Ends if

    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);

    view_log_list_table($logObjects, $totalNumberOfPages, $currentPage);
    view_log_list_filter_modal();

} // Ends view_log_list_main()

function view_log_list_recent() {
    
    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    $totalRows = $db->getActivityLogObjectsCount($currentUser[0]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the log objects for the current page
    $logObjects = $db->getActivityLogObjectsAsTable($currentUser[0], $currentPage, $recordsPerPage);

    view_log_list_table($logObjects, $totalNumberOfPages, $currentPage);
    view_log_list_filter_modal();

} // Ends view_log_list_recent

function view_log_list_created_today() {
    
    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    $totalRows = $db->getLogsCreatedTodayCount($currentUser[0], $currentUser[6]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the log objects for the current page
    $logObjects = $db->getLogsCreatedTodayAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);

    view_log_list_table($logObjects, $totalNumberOfPages, $currentPage);
    view_log_list_filter_modal();

} // Ends view_log_list_recent

function view_log_list_table($logObjects, $totalNumberOfPages, $currentPage) {

    $checkedRecent = $checkedStudent = $checkedType = "\"";

    if (isset($_GET["sortBy"])) {

        $sortBy = $_GET["sortBy"];

        switch ($sortBy) {

            case "mostRecent":
                $checkedRecent = "checked";
                break;
            case "student":
                $checkedStudent = "checked";
                break;
            case "type":
                $checkedType = "checked";
                break;

        } // Ends switch
        
    } // Ends if

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <div class="container pt-4 d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-4">

                <p class="h3 m-auto">Logs</p>

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
                                <input class="form-check-input" type="radio" name="SortBy" id="MostRecent" onclick="window.location.href=\'https://seniordevteam1.in/views/log_list_ui.php?log&sortBy=mostRecent\'" '.$checkedRecent.' />
                                <label class="form-check-label" for="MostRecent"> Most Recent </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Student" onclick="window.location.href=\'https://seniordevteam1.in/views/log_list_ui.php?log&sortBy=student\'" '.$checkedStudent.' />
                                <label class="form-check-label" for="Student"> Student </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Type" onclick="window.location.href=\'https://seniordevteam1.in/views/log_list_ui.php?log&sortBy=type\'" '.$checkedType.' />
                                <label class="form-check-label" for="Type"> Type </label>
                            </div>
                        </li>
                    </ul>

                </div>

                <a class="btn btn-outline-dark btn-rounded ripple-surface" type="button" cursor: pointer; data-mdb-toggle="modal" data-mdb-target="#logModal">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-filter" ></i>
                        <p class="lh-1 fs-6 m-auto">Filter</p>
                    </div>
                </a>
    ');

    // Displays chips for each filter that is added
    if (isset($_COOKIE["logSearchGeneralCookie"])) {

        if (isset($_COOKIE["logSearchTimeCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Time: '.$_COOKIE["logSearchTimeCookie"].'
                </div>
            ');

        } // Ends if

        if (isset($_COOKIE["logSearchTypeCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Type: '.$_COOKIE["logSearchType"].'
                </div>
            ');

        } // Ends if

        if (isset($_COOKIE["logSearchUsernameCookie"]) && !empty($_COOKIE["logSearchUsernameCookie"])) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Username: '.$_COOKIE["logSearchUsernameCookie"].'
                </div>
            ');

        } // Ends if

        if (isset($_GET['recent'])) {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/views/log_list_ui.php\'">
                    Clear Filters
                    <span class="closebtn">&times;</span>
                </div>
            ');

        } else {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/controllers/filter_controller.php?clearLog\'">
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

        <!-- Log Table -->
        <div class="container pt-2 long-table-container">
            <div class="table-responsive search-table">

                <table id="logsListTable" class="table table-hover">
                    '.$logObjects.'
                </table>

            </div>
        </div>

    </main>
    ');

} // Ends view_log_list_table

function view_log_list_filter_modal() {
    
    echo '
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log Search Filters</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="https://seniordevteam1.in/controllers/filter_controller.php?setLog" method="post">

                    <div class="modal-body">

                            <!--Log Time Dropdown Filter-->
                            <label for="logSearchTime">Log Time:</label>
                            <select name="logSearchTime" id="searchLogTime">
                                <option value="Any">Any</option>
                                <option value="Last Day">Last Day</option>
                                <option value="Last Three Days">Last 3 Days</option>
                                <option value="Last Week">Last Week</option>
                                <option value="Last Month">Last Month</option>
                            </select>

                            <br>

                            <!--Log Type Dropdown Filter-->
                            <label for="logSearchType">Log Type:</label>
                            <select name="logSearchType" id="searchLogType">
                                <option value="Any">Any</option>
                                <option value="Failed Login">Failed Login</option>
                                <option value="Successful Login">Successful Login</option>
                                <option value="File Created">File Created</option>
                                <option value="File Modified">File Modified</option>
                            </select>

                            <br>

                            <!--Log Type Dropdown Filter-->
                            <label for="logSearchUsername">Username:</label>
                            <input type="search" name="logSearchUsername" id="logUserSearchBar" placeholder="Username">

                            <br>

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

} // Ends view_log_list_filter_modal

?>