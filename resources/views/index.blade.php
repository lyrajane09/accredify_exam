<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Accredify Examination</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            input[type="submit"] {
                padding: 10px 30px;
                cursor: pointer;
                border-radius: 5px;
                border: 1px solid #000;
            }
            input[type="text"] {
                border: 1px solid #000;
                padding: 10px;
                margin-top: 5px;
            }
            .errors-container {
                text-align: left;
            }
            #errors {
                color: #800000;
            }
        </style>
        <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
        
    </head>
    <body class="antialiased">
        <div class="relative items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-5 lg:px-5">
                <div class="container">
                    <form id="certificate-form">
                        @csrf
                        <div class="text-center text-sm text-gray-500 sm:text-left">
                            <h1>Accredify Exam</h1>
                            <div class="flex items-center">
                                <h2>Login</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-5">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="">Email</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="w-100" name="email">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2">
                                            <label for="">Password</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" class="w-100 h-100" name="password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-6 errors-container">
                                            <label for="" id="errors"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="submit" value="Submit" id="submit-btn">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 mt-5">
                                    <div class="card w-75 mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Test accounts</h5>
                                            <small class="card-text">lyra@accredify.com</small><br/>
                                            <small class="card-text">john@accredify.com</small><br/>
                                            <small class="card-text">bryan@accredify.com</small><br/><br/>
                                            <small><b>Password: password</b></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $('#certificate-form').on('submit', function() {
                    $.ajax({
                        url: '/api/auth/login',
                        method: 'POST',
                        type: 'json',
                        data: JSON.stringify({
                            "email": $('input[name="email"]').val(),
                            "password": $('input[name="password"]').val()
                        }),
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        success: function (response){
                            localStorage.setItem('token', response.token);
                            window.location.href = "/submit-certificate";
                            $('#submit-btn').val('Submit');
                            $('#submit-btn').removeAttr('disabled');
                            
                        },
                        beforeSend: function() {
                            $('#submit-btn').val('Please wait...');
                            $('#submit-btn').attr('disabled', "disabled");
                        },
                        error: function (error) {
                            let errors;

                            if (error.status === 401) {
                                if (error.responseJSON.errors !== undefined) {
                                    errors = Object.values(error.responseJSON.errors).join("<br>");
                                } else {
                                    errors = "Email & Password does not match with our record.";
                                }
                            }
                        
                            $('#errors').html(errors);
                            $('#submit-btn').val('Submit');
                            $('#submit-btn').removeAttr('disabled');
                        }
                    });

                    return false;
                });
            });
        </script>
    </body>
</html>
