<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome</title>

        <!-- Fonts -->

        <!-- Styles -->
        <style>
        body {
            margin:0;
        }
        * {
            box-sizing: border-box;
        }
        .flex-center{
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            height: 100vh;
            align-items: center;
            background-color: #314f75;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            color: white;
        }
        .flex-center p {
            font-size: 17px;
            font-weight: 600;
        }
        .btn {
            padding: 8px 20px;
            background-color: royalblue;
            color: white;
            border-radius: 6px;
        }
        .app-name {
            font-size: 27px;
            margin: 1rem auto;
        }
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
    <div class="flex-center">
     <div> Selamat datang di</div>
     <div class="app-name">{{ config('app.name')}}</div>
    </div>
    </body>
</html>
