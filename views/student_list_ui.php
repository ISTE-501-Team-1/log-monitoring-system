<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Search Student", true, 2);

if (isset($_GET['recent'])) {
    view_student_list_recent();    
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

    $totalRows = $db->getStudentObjectsByRoleCount($currentUser[0], $currentUser[6]);
    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);
    
    // Get the student objects for the current page
    $studentObjects = $db->getStudentObjectsByRoleAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);

    view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage);
    view_student_list_filter_modal();

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

    view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage);
    view_student_list_filter_modal();

} // Ends view_student_list_recent

function view_student_list_table($studentObjects, $totalNumberOfPages, $currentPage) {

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

                <span><i class="fas fa-filter" style="padding-right: 1em; padding-left: 3em; padding-top: 0.5em; cursor: pointer;" data-mdb-toggle="modal" data-mdb-target="#studentModal"></i></span>

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

function view_student_list_filter_modal() {
    
    echo '
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Student Search Filters</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <label for="studentSearch">Username:</label>
                    <input type="search" id="studentSearchBar" placeholder="Username">

                    <br>

                    <!--Class Dropdown Filter-->
                    <label for="studentClass" style="padding-top:1em">Class:</label>
                    <input type="search" id="studentClassSearch" name="studentClass" placeholder="Class ID">
                    
                    <br>

                    <!--Last Log Dropdown Filter-->
                    <label for="studentLastLog" style="padding-top:1em">Last Log:</label>
                    <select name="studentLastLog" id="studentLastLog">
                        <option value="anyTime">Any</option>
                        <option value="lastHour">Last Hour</option>
                        <option value="lastTwelveHours">Last Twelve Hours</option>
                        <option value="last24Hours">Last 24 Hours</option>
                        <option value="lastWeek">Last Week</option>
                    </select>

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

} // Ends view_student_list_filter_modal

?>