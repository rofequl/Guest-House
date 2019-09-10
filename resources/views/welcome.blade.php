<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Army Golf Club | Guest Room Manage</title>
        <meta name="description"
              content="Army Golf Club | Guest Room Manage">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link href="{{asset('css/all.css')}}" rel="stylesheet">

    </head>
    <body>
        <div class="container-fluid" id="app">
            <main-component></main-component>
            <vue-progress-bar></vue-progress-bar>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/all.js')}}"></script>
    </body>
</html>
