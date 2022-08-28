<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>日付別勤務時間一覧</title>
</head>
<body>
  <header class="header">
    <div class="header__inner">
      <h1 class="header__title">Atte</h1>
      <nav class="header__nav">
        <a href="" class="home">ホーム</a>
        <a href="{{ url('/work/calendar')}}" class="calendar">日付一覧</a>
        <a href="/logout" class="logout">ログアウト</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <div class="mainWrapper">
      <div class="dateOuter">
        <form action="/work/attendance/date/prev" method="POST" class="datePrev">
          <input type="hidden" name="date" value="{{$date->subMonthNoOverflow()}}">
          <input class="btnPrev" type="submit" value="<">
        </form>
        <div class="date">{{ $date }}</div>
        <form action="/work/attendance/date/next" method="POST" class="dateNext">
          <input type="hidden" name="date" value="{{$date->addMonthNoOverflow()}}">
          <input class="btnNext" type="submit" value=">">
        </form>
      </div>
      <div class="contentAttendance">
        <table>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
          @foreach($attendance as $attendance)
          <tr>
            <td>{{ $attendance->name }}</td>
            <td>{{ $attendance->start_time }}</td>
            <td>{{ $attendance->end_time }}</td>
            <td>{{ $attendance->break_in }}</td>
            <td>{{ $attendance->break_out }}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
    
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>