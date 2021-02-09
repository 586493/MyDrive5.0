<!DOCTYPE html>
<html>
<head>
    <noscript>
        <meta http-equiv="refresh" content="0; url={{$noscriptRedirect}}">
    </noscript>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ asset('res/img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('res/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('res/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('res/css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('res/css/files.css') }}">
    <link rel="stylesheet" href="{{ asset('res/css/other.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="noSelect" style="background-color: var(--c07)">
