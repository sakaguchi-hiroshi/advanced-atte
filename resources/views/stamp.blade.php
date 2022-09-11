<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>勤怠打刻ページ</title>
  <link rel="stylesheet" href="{{ asset('/assets/css/reset.css')}}">
  <link rel="stylesheet" href="{{ asset('/assets/css/style.css')}}">
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
    <p class="message-user">{{$authuser->name}}さんお疲れ様です！
    </p>
    <p>{{ session('message')}}</p>
    <section class="stamp">
      <form class="stamp__start-time" action="/work/start" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button class="btn-stamp" type="submit" >勤務開始</button>
      </form>
      <form class="stamp__end-time" action="/work/end" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button class="btn-stamp" type="submit">勤務終了</button>
      </form>
      <form class="stamp__break-in" action="/work/break/in" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button class="btn-stamp" type="submit">休憩開始</button>
      </form>
      <form class="stamp__break-out" action="/work/break/out" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button class="btn-stamp" type="submit">休憩終了</button>
      </form>
    </section>
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>