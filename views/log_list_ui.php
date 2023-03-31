<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Search Logs", true, 1);

if (isset($_GET['recent'])) {
    view_log_list_recent();    
} else if (isset($_GET['today'])) {
    view_log_list_created_today();
} else {
    view_log_list_main();
} // Ends if

view_common_footer();

function view_log_list_main() {

    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    $totalRows = $db->getLogObjectsByRoleCount($currentUser[0], $currentUser[6]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the log objects for the current page
    $logObjects = $db->getLogObjectsByRoleAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);

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
                                <input class="form-check-input" type="radio" name="SortBy" id="MostRecent" onclick="window.location.href=\'../controllers/search_controller.php?log&sortBy=time\'" checked />
                                <label class="form-check-label" for="MostRecent"> Most Recent </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Username" onclick="window.location.href=\'../controllers/search_controller.php?log&sortBy=username\'" />
                                <label class="form-check-label" for="Username"> Username </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="School" onclick="window.location.href=\'../controllers/search_controller.php?log&sortBy=\'" />
                                <label class="form-check-label" for="School"> School </label>
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

                <div class="modal-body">
                    <form action="../controllers/search_controller.php?log" method="post">

                        <!--Log Time Dropdown Filter-->
                        <label for="logSearchTime">Log Time:</label>
                        <select name="logSearchTime" id="searchLogTime">
                            <option value="any">Any</option>
                            <option value="lastDay">Last Day</option>
                            <option value="lastThreeDays">Last 3 Days</option>
                            <option value="lastWeek">Last Week</option>
                            <option value="lastMonth">Last Month</option>
                        </select>

                        <br>

                        <!--Log Type Dropdown Filter-->\
                        <label for="logSearchType">Log Type:</label>
                        <select name="logSearchType" id="searchLogType">
                            <option value="any">Any</option>
                            <option value="failedLogin">Failed Login</option>
                            <option value="successfulLogin">Successful Login</option>
                            <option value="fileCreated">File Created</option>
                            <option value="fileModified">File Modified</option>
                        </select>

                        <br>

                        <!--Log Type Dropdown Filter-->
                        <label for="logSearchUsername">Username:</label>
                        <input type="search" name="logSearchUsername" id="logUserSearchBar" placeholder="Username">

                        <br>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-warning" data-mdb-ripple-color="dark" data-mdb-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning">Apply Filter</button>
                </div>

            </div>
        </div>
    </div>
    ';

} // Ends view_log_list_filter_modal

?>