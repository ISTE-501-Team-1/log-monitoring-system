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

                <p class="h3 m-auto">All Logs</p>

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
                                <input class="form-check-input" type="radio" name="SortBy" id="MostRecent" checked />
                                <label class="form-check-label" for="MostRecent"> Most Recent </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Username"/>
                                <label class="form-check-label" for="Username"> Username </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="School"/>
                                <label class="form-check-label" for="School"> School </label>
                            </div>
                        </li>
                    </ul>

                </div>

                <span><i class="fas fa-filter" style="padding-right: 1em; padding-left: 3em; padding-top: 0.5em; cursor: pointer;" data-mdb-toggle="modal" data-mdb-target="#logModal"></i></span>

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
                    
                    <!--Log Date Dropdown Filter-->
                    <label for="logDateStart logDateEnd">Log Date:</label>
                    <input type="date" id="datePicker" name="logDateStart" value="2023-03-18" min="2023-01-01" max="2023-12-31">
                        To
                    <input type="date" id="datePicker" name="logDateEnd" value="2023-03-18" min="2023-01-01" max="2023-12-31">
                    
                    <br>

                    <!--Log Time Dropdown Filter-->
                    <label for="logTime">Log Time:</label>
                    <select name="logTime" id="searchLogTime">
                        <option value="anyTime">Any</option>
                        <option value="lastHour">Last Hour</option>
                        <option value="lastSixHours">Last Six Hours</option>
                        <option value="lastTwelveHours">Last Twelve Hours</option>
                        <option value="last24Hours">Last 24 Hours</option>
                    </select>

                    <br>

                    <!--Log Type Dropdown Filter-->\
                    <label for="logType">Log Type:</label>
                    <select name="logType" id="searchLogType">
                        <option value="anyType">Any</option>
                        <option value="failed">Failed</option>
                        <option value="successful">Successful</option>
                    </select>

                    <br>

                    <!--Log Type Dropdown Filter-->
                    <label for="logUser">Username:</label>
                    <input type="search" id="logUserSearchBar" placeholder="Username">

                    <br>
        
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