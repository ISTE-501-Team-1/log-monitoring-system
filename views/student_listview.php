<?php

require_once "../models/PDO.DB.class.php";
require_once "../views/common_ui.php";
view_common_includes();
view_common_header();
view_common_navigation();
view_student_listview_main();
view_common_footer();

function view_student_listview_main() {

    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <div class="table-responsive">
            <table id="studentTable" class="table table-hover text-nowrap" >
            './/$db->getAllStudentObjectsAsTable()).
            '</table>
        </div>

    </main>
    ');

} //ends view_student_listview_main()

?>

