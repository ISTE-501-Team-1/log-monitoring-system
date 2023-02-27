<?php

function view_login_main() { 
    
    echo('
        <section class="vh-100" style="background-color: #F98029;">

            <div class="container py-5 h-100">

                <div class="row d-flex justify-content-center align-items-center h-100">

                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                        <div class="card shadow-2-strong" style="border-radius: 1rem;">

                            <div class="card-body p-5 text-center">

                                <h3 class="mb-5">Sign in</h3>

                                <!-- Username -->
                                <div class="form-outline mb-4">
                                    <input type="name" id="typeTextX-2" class="form-control form-control-lg" />
                                    <label class="form-label" for="typeTextX-2">Username</label>
                                </div>

                                <!-- Password -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" class="form-control form-control-lg" />
                                    <label class="form-label" for="typePasswordX-2">Password</label>
                                </div>

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3"> Remember password </label>
                                </div>

                                <!-- Login Button -->
                                <a class="btn btn-primary btn-lg btn-block" type="submit" href="./admin.html">Login</a>

                                <!-- Request Access Link -->
                                <div class="text-start register">
                                    <p>Don\'t have an account? <a href="#!">Request Access</a></p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
    ');

} // Ends view_login_main

?>