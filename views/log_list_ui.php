<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Log List", true, 1);

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

} // Ends view_log_list_recent

function view_log_list_table($logObjects, $totalNumberOfPages, $currentPage) {

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <div class="container pt-4">
            <div class="table-responsive table">

                <table class="table table-bordered table-hover mb-0">
                    '.$logObjects.'
                </table>

                <nav aria-label="Pagination">
                    <ul class="pagination pagination-circle justify-content-center">
                        '.get_common_pagination($totalNumberOfPages, $currentPage).'
                    </ul>
                </nav>

            </div>
        </div>

    </main>
    ');

} // Ends view_log_list_table

?>