<?php

require_once "../views/common_ui.php";
view_common_includes('../');
view_common_header();
view_common_navigation("Log Details", true, 1);

function view_log_details_main() {

    $db = new DB();

    // Need log ID, log type, time created, time edited, login attempt ID student ID and student name 
    $currentLog = $db->getLogByID($_GET["id"]);

    $logTypeString = "";
    $logDetailsOutput = "";

    if ($currentLog[1] == 0) {

        $logTypeString = "Login";
        $currentLoginAttempt = $db->getLoginAttemptByID($currentLog[3]);

        $logDetailsOutput = '
                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Attempt ID:</p>
                        <p class="fs-6 lh-1">'.$currentLoginAttempt[0].'</p>
                    </div>

                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Attempt Outcome:</p>
                        <p class="fs-6 lh-1">'.$currentLoginAttempt[4].'</p>
                    </div>

                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Attempt Username:</p>
                        <p class="fs-6 lh-1">'.$currentLoginAttempt[1].'</p>
                    </div>

                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Attempt Password:</p>
                        <p class="fs-6 lh-1">'.$currentLoginAttempt[2].'</p>
                    </div>
        ';

    } else if ($currentLog[1] == 1) {
        $logTypeString = "File Created";
    } else if ($currentLog[1] == 2) {
        $logTypeString = "File Modified";
    } // Ends if

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">
        <div class="container pt-4">
            <div class="card">

                <div class="card-header table-header text-center py-3">

                    <div class ="table-header-title">
                        <i class="far fa-file-alt fa-fw me-3"></i>
                        <h5 class="mb-0 text-center">
                            <strong>Log ID: '.$currentLog[0].'</strong>
                        </h5>
                    </div>
                    <button type="button" class="btn btn-floating chevron-btn" data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="Options">
                        <i class="fas fa-ellipsis-h fa-lg"></i>
                    </button>
                </div>

                <div id="log-card-body" class="card-body d-flex flex-column">

                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Log Type:</p>
                        <p class="fs-6 lh-1">'.$logTypeString.'</p>
                    </div>
    ');

    echo ($logDetailsOutput);

    echo ('
                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Log Time Created:</p>
                        <p class="fs-6 lh-1">'.$currentLog[2].'</p>
                    </div>
    ');

    if ($db->getStudentByID($currentLog[4]) !== null) {
        
        $currentStudent = $db->getStudentByID($currentLog[4]);

        echo ('
                    <div class="d-flex gap-2">
                        <p class="h6 lh-1">Student:</p>
                        <a type="button" class="btn btn-outline-primary btn-rounded fs-6 lh-1" data-mdb-ripple-color="dark" data-mdb-toggle="tooltip" data-mdb-placement="right" title="Go to Student" href="https://seniordevteam1.in/views/student_details_ui.php?id='.$currentLog[4].'">
                            '.$currentStudent[4].'
                        </a>
                    </div>
        ');

    } // Ends if

    echo ('   
                </div>

            </div>
        </div>
    </main>
    <!--Main layout-->
    ');

} // Ends view_log_details_main