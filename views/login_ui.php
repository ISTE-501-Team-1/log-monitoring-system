<?php

function view_login_main() { 
    
    echo('

    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta http-equiv="x-ua-compatible" />
            <title>Log Management System</title>
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
            <!-- Google Fonts Roboto -->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
            <!-- MDB -->
            <link rel="stylesheet" href="https://seniordevteam1.in/src/css/mdb.min.css" />
            <!-- Custom styles -->
            <link rel="stylesheet" href="https://seniordevteam1.in/src/css/realLogin.css" />
        </head>

        <header>
            <nav class="navbar navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="https://seniordevteam1.in/src/img/Logo_LMS.svg" height="35" alt="LMS Logo" loading="lazy" />
                    </a>
                </div>
            </nav>
        </header>
        <body>

            <section class="vh-100" style="background-color: #F98029;">

                <div class="container py-5 h-100">

                    <div class="row d-flex justify-content-center align-items-center h-100">

                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                            <div class="card shadow-2-strong" style="border-radius: 1rem;">

                                <div class="card-body p-5 text-center">

                                    <h3 class="mb-5">Sign in</h3>

                                    <form action="../controllers/login_controller.php" method="post">

                                        <!-- Username -->
                                        <div class="form-outline mb-4">
                                            <input type="name" id="typeTextX-2" name="userUsername" class="form-control form-control-lg" />
                                            <label class="form-label" for="typeTextX-2">Username</label>
                                        </div>

                                        <!-- Password -->
                                        <div class="form-outline mb-4">
                                            <input type="password" id="typePasswordX-2" name="userPassword" class="form-control form-control-lg" />
                                            <label class="form-label" for="typePasswordX-2">Password</label>
                                        </div>

                                        <!-- Checkbox -->
                                        <div class="form-check d-flex justify-content-start mb-4">
                                            <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                            <label class="form-check-label" for="form1Example3"> Remember password </label>
                                        </div>

                                        <!-- Login Button -->
                                        <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>

                                        <!-- Request Access Link -->
                                        <div class="text-start register">
                                            <p>Don\'t have an account? <a href="#!">Request Access</a></p>
                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <!-- MDB Script -->
            <script type="text/javascript" src="https://seniordevteam1.in/src/js/mdb.min.js"></script>

        </body>
    </html>
    ');

} // Ends view_login_main

?>