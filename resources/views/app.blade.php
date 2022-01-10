<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://unpkg.com/primeicons/primeicons.css" rel="stylesheet">
  <title>Laravel</title>
  @vite
  @routes
</head>
<body class="antialiased">
  @inertia
</body>
</html>
