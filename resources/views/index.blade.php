<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>{{ config('app.name') }}</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  <div id="app"></div>

  @include('scripts')
</body>
</html>
