<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Student List", true);
view_student_list_main();
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

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <div class="container pt-4">
            <div class="table-responsive table">

                <table class="table table-bordered mb-0">
                    '.$studentObjects.'
                </table>

                <nav aria-label="Pagination">
                    <ul class="pagination pagination-circle">
                        '.getPaginationLinks($totalNumberOfPages, $currentPage).'
                    </ul>
                </nav>

            </div>
        </div>

    </main>
    ');

} // Ends view_student_list_main()

function getPaginationLinks($numPages, $currentPage) {

    $links = '';
    
    for ($i = 1; $i <= $numPages; $i++) {

        $isActive = ($i == $currentPage) ? 'active' : '';
        $links .= '<li class="page-item '.$isActive.'"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';

    } // Ends for

    return $links;

} // Ends getPaginationLinks

?>