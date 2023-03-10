<?php

//require_once "../models/PDO.DB.class.php";
//require_once "../views/common_ui.php";
view_common_includes("../");
view_common_header();
view_common_navigation("Dashboard", false);
view_dashboard_main();
view_common_footer();

// TODO: Add links for "Server Activity", "Logins", and "Failed Logins" cards, and similarly for the "View More" button on the "Recently Viewed..." tables
function view_dashboard_main() { 
    
    $db = new DB();

    $currentUser = $db->getUserByID($_COOKIE["loggedInUserID"]);

    echo('
    <!--Main layout-->
    <main style="margin-top: 58px">

        <!--Section: Stat Cards-->
        <div class="container pt-4">
        
            <div class="d-flex pt-xl-4 align-items-center gap-2">
                <p class="h2">Welcome, '.$currentUser[1].' '.$currentUser[2].'</p>
                <p class="h5">
                    <span class="badge rounded-pill bg-info">'.$currentUser[6].'</span>
                </p>
            </div>
        
            <div id="dashboard-stats" class="row align-items-stretch pt-xl-4 pt-md-2">
                
                <!--Logs created card-->
                <div class="col col-xl-4 col-md-12 mb-4">
                    <div class="card h-100" type="button">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line text-info fa-3x me-4"></i>
                                    </div>
                                    <div>
                                        <h4>Server Activity</h4>
                                        <p class="mb-0">Logs Created Today</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0">'.$db->getCountLogsCreatedToday($currentUser[0], $currentUser[6]).'</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <!--Successful logins card-->
                <div class="col col-xl-4 col-md-12 mb-4">
                    <div class="card h-100" type="button">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <i class="fas fa-user-plus text-success fa-3x me-4"></i>
                                    </div>
                                    <div>
                                        <h4>Logins</h4>
                                        <p class="mb-0">Successful Logins Today</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0">'.$db->getCountLoginAttemptsToday("success", $currentUser[0], $currentUser[6]).'</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <!--Failed logins card-->
                <div class="col col-xl-4 col-md-12 mb-4">
                    <div class="card h-100" type="button">
                        <div class="card-body">
                            <div class="d-flex justify-content-between p-md-1">
                                <div class="d-flex flex-row">
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation-circle text-danger fa-3x me-4"></i>
                                    </div>
                                    <div>
                                        <h4>Failed Logins</h4>
                                        <p class="mb-0">Failed Logins Today</p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <h2 class="h1 mb-0">'.$db->getCountLoginAttemptsToday("failure", $currentUser[0], $currentUser[6]).'</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
            </div>
            <!--Section: Stat Cards-->
        
            <!--Section: Recently Viewed Student Table-->
            <section class="mb-4">
                <div class="card">

                    <div class="card-header table-header text-center py-3">
                        <div class ="table-header-title">
                            <i class="fas fa-users fa-fw me-3"></i>
                            <h5 class="mb-0 text-center">
                                <strong>Recently Viewed Students</strong>
                            </h5>
                        </div>
                        <button type="button" class="btn btn-floating chevron-btn" data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="View More">
                            <i class="fas fa-chevron-right fa-lg"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="studentDashboardTable" class="table table-hover text-nowrap" >'
                                .$db->getAllActivityRecentStudents($currentUser[0], 5).
                            '</table>
                        </div>
                    </div>

                </div>
            </section>
            <!--Section: Recently Viewed Student Table-->
        
            <!--Section: Recently Viewed Logs Table-->
            <section class="mb-4">
                <div class="card">
                    
                    <div class="card-header table-header text-center py-3">
                        <div class ="table-header-title">
                            <i class="far fa-file-alt fa-fw me-3"></i>
                            <h5 class="mb-0 text-center">
                                <strong>Recently Viewed Logs</strong>
                            </h5>
                        </div>
                        <button type="button" class="btn btn-floating chevron-btn" data-mdb-toggle="tooltip" data-mdb-placement="bottom" title="View More">
                            <i class="fas fa-chevron-right fa-lg"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="logDashboardTable" class="table table-hover text-nowrap" >'
                                .$db->getAllActivityRecentLogs($currentUser[0], 5).
                            '</table>
                        </div>
                    </div>

                </div>
            </section>
            <!--Section: Recently Viewed Logs Table-->
        
        </div>
        <!--Section: Stat Cards-->

    </main>
    ');

} // Ends view_dashboard_main

?>

