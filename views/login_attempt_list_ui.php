<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Login Attempt List", true, 0);

if (isset($_GET['today'])) {
    view_login_attempt_list_created_today($_GET['today']);
} else {
    view_login_attempt_list_created_today("all");
} // Ends if

view_common_footer();

function view_login_attempt_list_created_today($successType) {
    
    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    // Get the current page number and number of records per page from the query string
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $recordsPerPage = 20;

    $totalRows = $db->getLoginAttemptsTodayCount($successType, $currentUser[0], $currentUser[6]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the login attempt objects for the current page
    $logObjects = $db->getLoginAttemptsTodayAsTable($successType, $currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);

    view_login_attempt_list_table($logObjects, $totalNumberOfPages, $currentPage);

} // Ends view_login_attempt_list_created_today

function view_login_attempt_list_table($logObjects, $totalNumberOfPages, $currentPage) {

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

} // Ends view_login_attempt_list_table

?>