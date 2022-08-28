<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>勤怠打刻ページ</title>
</head>
<body>
  <header class="header">
    <div class="header__inner">
      <h1 class="header__title">Atte</h1>
      <nav class="nav">
        <a href="" class="home">ホーム</a>
        <a href="{{ url('/work/calendar')}}" class="calendar">日付一覧</a>
        <a href="/logout" class="logout">ログアウト</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <p class="user-name">{{$authuser->name}}さんお疲れ様です！
    </p>
    <p>{{ session('message')}}</p>
    <div class="card">
      <form class="start_time" action="/work/start" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button type="submit" >勤務開始</button>
      </form>
      <form class="end_time" action="/work/end" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button type="submit">勤務終了</button>
      </form>
      <form class="break_in" action="/work/break/in" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button>休憩開始</button>
      </form>
      <form class="break_out" action="/work/break/out" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <button>休憩終了</button>
      </form>
    </div>
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>