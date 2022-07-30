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
        <a href="" class="calendar">日付一覧</a>
        <a href="" class="logout">ログアウト</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <p class="user-name">{{$authuser->name}}さんお疲れ様です！
    </p>
    <div class="card">
      <form class="start_time" action="/work/start" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <input type="hidden" name="date" value="{{$date}}">
        <button type="submit" name="start_time" value="{{$datetime}}">勤務開始</button>
      </form>
      <form class="end_time" action="/work/end">
        <input type="hidden" name="user_id" value="{{$authuser->id}}">
        <input type="hidden" name="date" value="{{$date}}">
        <button type="submit" name="end_time" value="{{$datetime}}">勤務終了</button>
      </form>
      <form class="break_in" action="">
        <input type="hidden">
        <button>休憩開始</button>
      </form>
      <form class="break_out" action="">
        <input type="hidden">
        <button>休憩終了</button>
      </form>
    </div>
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>