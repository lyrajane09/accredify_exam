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
                padding: 5px 30px;
                cursor: pointer;
                border-radius: 5px;
                border: 1px solid #000;
                margin-top: 10px;
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
            #result {
                text-align: left;
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
                    <form id="certificate-form" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center text-sm text-gray-500 sm:text-left">
                            <h1>Accredify Exam</h1>
                            <div class="flex items-center">
                                <h2>File upload</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mt-5">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="">File</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="file" class="w-100" name="file">
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
                                            <pre id="result"></pre>
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
                    console.log(localStorage.getItem('token'));
                    var formData = new FormData(this);
                    
                    $.ajax({
                        url: '/api/certificate',
                        method: 'POST',
                        type: 'json',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            "Accept": "application/json",
                            "Authorization": "Bearer "+localStorage.getItem('token')
                        },
                        async: true,
                        success: function (response){
                            $('#result').html(JSON.stringify(response, null, '\t'));
                            $('#submit-btn').val('Submit');
                            $('#submit-btn').removeAttr('disabled');
                        },
                        beforeSend: function() {
                            $('#submit-btn').val('Please wait...');
                            $('#submit-btn').attr('disabled', "disabled");
                        },
                        error: function (error) {
                            let errors;
                            if (error.status === 422) {
                                if (error.responseJSON.errors !== undefined) {
                                    errors = error.responseJSON;
                                } else {
                                    errors = "Something went wrong!";
                                }
                            }

                            $('#result').html(JSON.stringify(errors, null, 6)
                                        .replace(/\n( *)/g, function (match, p1) {
                                            return '<br>' + '&nbsp;'.repeat(p1.length);
                                        }));
                            document.getElementById("result").textContent = JSON.stringify(errors, undefined, 2);
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
