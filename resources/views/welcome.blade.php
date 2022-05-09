<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Prueba teko</title>

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
                    </div>
                </div>
           </div>
        </div>
    </body>
</html>
