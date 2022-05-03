<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel - CRUD API Project</title>

        <!-- Font-Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body>
        <div class="app-container">
           <div class="center mt-5">
                <div class="card m-5">
                    <div class="card body">
                        <div style="text-align: center">
                            <img src="https://laravel.com/img/logomark.min.svg" style="max-width: 600px"/>
                            <img src="https://laravel.com/img/logotype.min.svg" style="max-width: 600px"/>
                        </div>
                        <h2 class="mt-3">API with Authentication</h2>
                        <hr>
                        <p>API Documentation</p>
                        <div class="card card-body m-2">
                            <div class="row">
                                <div class="col-5">
                                    <h4>Authentication Module</h4>
                                </div>
                                <div class="col-7">
                                    <ul class="feature-list">
                                        <li>Login API with Token</li>
                                        <li>Authenticated User Profile</li>
                                        <li>Refresh Data</li>
                                        <li>Logout</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <p class="text-center">
                            <a href="{{ route('l5-swagger.default.api') }}" class="btn btn-primary">
                              <i class="fa fa-book"></i>  Read API Documentation
                            </a>
                        </p>
                    </div>
                </div>
           </div>
        </div>
    </body>
</html>
