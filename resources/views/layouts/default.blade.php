<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('/assets/css/reset.css')}}">
  <link rel="stylesheet" href="{{ asset('/assets/css/default.css')}}">
  
</head>
<body>
  <header class="header">
    <div class="header__inner">
      <h1 class="header__title">Atte</h1>
      <nav class="header__nav">
        <a href="{{ url('/work/stamp')}}" class="home">ホーム</a>
        <a href="{{ url('/work/calendar')}}" class="calendar">日付一覧</a>
        <a href="/logout" class="logout">ログアウト</a>
      </nav>
    </div>
  </header>
  <main class="main">
    @yield('message')
    @yield('selected')
    @yield('date')
    <div class="container">
      @yield('stamp')
      @yield('calendar')
      @yield('attendance')
    </div>
  </main>
  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>