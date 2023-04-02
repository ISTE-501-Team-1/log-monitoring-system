<?php

require_once "../views/common_ui.php";
require_once "../controllers/validation_controller.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Search Student", false, 2);

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

    if (isset($_GET["student"])) {

        $filterByUsername = $filterByClass = $filterByLog = $sortBy = "";

        if (isset($_GET["sortBy"])) {
            $sortBy = $_GET["sortBy"];
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($_POST["studentSearchUsername"]) {
                $filterByUsername = sanitize_string($_POST["studentSearchUsername"]);
            }

            if ($_POST["studentSearchClass"]) {
                $filterByClass = sanitize_string($_POST["studentSearchClass"]);
            }
            
            //$filterByLog = sanitize_string($_POST["studentSearchLastLog"]);
        } // Ends if

        $studentObjects = $db->getStudentObjectsByRoleFilteredAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage, $sortBy, $filterByUsername, $filterByClass, $filterByLog);
        $totalRows = $db->getStudentObjectsByRoleFilteredCount($currentUser[0], $currentUser[6], $sortBy, $filterByUsername, $filterByClass, $filterByLog);
    
    } else {
        
        $studentObjects = $db->getStudentObjectsByRoleAsTable($currentUser[0], $currentUser[6], $currentPage, $recordsPerPage);
        $totalRows = $db->getStudentObjectsByRoleCount($currentUser[0], $currentUser[6]);
    
    }// Ends if

    $totalNumberOfPages = ceil($totalRows / $recordsPerPage);

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
                                <input class="form-check-input" type="radio" name="SortBy" id="StudentID" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=id\'" checked />
                                <label class="form-check-label" for="MostRecent"> ID </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="Username" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=username\'" />
                                <label class="form-check-label" for="Username"> Username </label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SortBy" id="School" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php?student&sortBy=school\'" />
                                <label class="form-check-label" for="School"> School </label>
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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST["studentSearchUsername"]) && !empty(sanitize_string($_POST["studentSearchUsername"]))) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Username: '.sanitize_string($_POST["studentSearchUsername"]).'
                </div>
            ');

        } // Ends if

        if (isset($_POST["studentSearchClass"]) && !empty(sanitize_string($_POST["studentSearchClass"]))) {

            echo('
                <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
                    Class ID: '.sanitize_string($_POST["studentSearchClass"]).'
                </div>
            ');

        } // Ends if

        // if (isset($_POST["studentSearchLastLog"]) && !empty(sanitize_string($_POST["studentSearchLastLog"]))) {

        //     echo('
        //         <div class="btn btn-rounded pe-none" type="button" style="background-color: lightblue;">
        //             Username: '.sanitize_string($_POST["studentSearchLastLog"]).'
        //         </div>
        //     ');

        // } // Ends if

        if (isset($_GET['recent'])) {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php\'">
                    Clear Filters
                    <span class="closebtn">&times;</span>
                </div>
            ');

        } else {

            echo('
                <div class="btn btn-rounded" type="button" style="background-color: lightblue;" onclick="window.location.href=\'https://seniordevteam1.in/views/student_list_ui.php\'">
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

function view_student_list_filter_modal() {
    
    echo '
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Student Search Filters</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="https://seniordevteam1.in/views/student_list_ui.php?student" method="post">

                    <div class="modal-body">

                            <label for="studentSearchUsername">Username:</label>
                            <input name="studentSearchUsername" type="search" id="studentSearchBar" placeholder="Username">

                            <br>

                            <!--Class Dropdown Filter-->
                            <label for="studentSearchClass" style="padding-top:1em">Class:</label>
                            <input type="search" id="studentClassSearch" name="studentSearchClass" placeholder="Class ID">
                            
                            <br>
                            
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-warning" data-mdb-ripple-color="dark" data-mdb-dismiss="modal">Close</button>
                        <button class="btn btn-warning" type="submit">Apply Filter</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    ';

// <!--Last Log Dropdown Filter-->
// <label for="studentSearchLastLog" style="padding-top:1em">Last Log:</label>
// <select name="studentSearchLastLog" id="studentLastLog">
//     <option value="any">Any</option>
//     <option value="lastDay">Last Day</option>
//     <option value="lastThreeDays">Last 3 Days</option>
//     <option value="lastWeek">Last Week</option>
//     <option value="lastMonth">Last Month</option>
// </select>

// <br>

} // Ends view_student_list_filter_modal

?>