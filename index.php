<?php

require_once "models/PDO.DB.class.php";
require_once "views/common_ui.php";
view_common_includes();

$db = new DB();

view_common_header();
view_common_navigation();
// Below will echo out the opening HTML tags and include the stylesheets
// echo('
//     <!DOCTYPE html>
//         <html lang="en">
//         <head>
//             <meta charset="UTF-8" />
//             <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
//             <meta http-equiv="x-ua-compatible" content="ie=edge" />
//             <title>Log Management System</title>
//             <!-- Font Awesome -->
//             <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
//             <!-- Google Fonts Roboto -->
//             <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
//             <!-- MDB -->
//             <link rel="stylesheet" href="css/mdb.min.css" />
//             <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
//             crossorigin="anonymous"></script>
//         </head>
//         <body>
//             <!--Main layout-->
//             <main style="margin-top: 58px">
//                 <div class="container pt-4">
// ');

// Below will display a table with class data from database
echo ('
                    <!--Section: Class Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>Class</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllClassObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: Class Table-->
');

// Below will display a table with classEntry data from database
echo ('
                    <!--Section: ClassEntry Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>ClassEntry</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllClassEntryObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: ClassEntry Table-->
');

// Below will display a table with file data from database
echo ('
                    <!--Section: File Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>File</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllFileObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: File Table-->
');

// Below will display a table with log data from database
echo ('
                    <!--Section: Log Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>Log</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllLogObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: Log Table-->
');

// Below will display a table with loginAttempt data from database
echo ('
                    <!--Section: LoginAttempt Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>LoginAttempt</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllLoginAttemptObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: LoginAttempt Table-->
');

// Below will display a table with school data from database
echo ('
                    <!--Section: School Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>School</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllSchoolObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: School Table-->
');

// Below will display a table with Student data from database
echo ('
                    <!--Section: Student Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>Student</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllStudentObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: Student Table-->
');

// Below will display a table with user data from database
echo ('
                    <!--Section: User Table-->
                    <section class="mb-4">
                        <div class="card">
                            <div class="card-header text-center py-3">
                                <h5 class="mb-0 text-center">
                                <strong>User</strong>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        '.$db->getAllUserObjectsAsTable().'
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--Section: User Table-->
');

// Below will echo out closing tags for html and includes MDB scripts
// echo('
//                 </div>
//             </main>
//             <!--Main layout-->
//             <!-- MDB -->
//             <script type="text/javascript" src="js/mdb.min.js"></script>
    
//         </body>
    
//     </html>
// ');

view_common_footer();

?>