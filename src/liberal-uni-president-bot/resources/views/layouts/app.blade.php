<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title') - {{ config('app.name') }}</title>
  <link rel="stylesheet" href="/css/styles.css">
  <script src="https://kit.fontawesome.com/d48d70bdc6.js" crossorigin="anonymous"></script>
  <script src="{{asset('/assets/js/jquery-3.4.1.min.js')}}"></script>
</head>
<body>
  <header>
    <nav class="my-navbar">
      <a class="my-navbar-brand" href="/">{{ config('app.name') }}</a>
    </nav>
  </header>
  <main>
        @yield('content')
  </main>
</body>
</html>
