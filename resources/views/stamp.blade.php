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
        <a href="" class="home"></a>
        <a href="" class="calendar"></a>
        <a href="" class="logout"></a>
      </nav>
    </div>
  </header>

  <main class="main">
    <p class="auth-user">{{$user->name}}さんお疲れ様です！
    </p>
    <div class="card">
      <form class="start_time" action="">
        <input type="hidden">
        <input type="hidden">
        <button>勤務開始</button>
      </form>
      <form class="end_time" action="">
        <input type="hidden">
        <input type="hidden">
        <button>勤務終了</button>
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