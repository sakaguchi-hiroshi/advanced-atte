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
        <a href="{{ url('/work/stamp')}}" class="home">ホーム</a>
        <a href="{{ url('/work/calendar')}}" class="calendar">日付一覧</a>
        <a href="/logout" class="logout">ログアウト</a>
      </nav>
    </div>
  </header>

  <main class="main">
    <div class="mainWrapper">
      <div class="dateOuter">
        <form action="/work/attendance/date/prev" method="POST" class="datePrev">
          @csrf
          <input type="hidden" name="date" value="{{$date->subDay()}}">
          <input class="btnPrev" type="submit" value="<">
        </form>
        <div class="date">{{ $date->addDay()->format('Y-m-d') }}</div>
        <form action="/work/attendance/date/next" method="POST" class="dateNext">
          @csrf
          <input type="hidden" name="date" value="{{$date->addDay()}}">
          <input class="btnNext" type="submit" value=">">
        </form>
      </div>
      <div class="contentAttendance">
        <table>
          <tr>
            <th>名前</th>
            <th>勤務開始</th>
            <th>勤務終了</th>
            <th>休憩時間</th>
            <th>勤務時間</th>
          </tr>
          @foreach($attendances as $workTime)
          <tr>
            <td>{{ $workTime->user->name }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeString($workTime->start_time)->format('H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeString($workTime->end_time)->format('H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeString($workTime->total_break_time)->format('H:i:s') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeString($workTime->total_hours_worked)->format('H:i:s') }}</td>
          </tr>
          @endforeach
        </table>
      </div>
      {{ $attendances->links() }}
    </div>
    
  </main>

  <footer class="footer">
    <small class="copyright">Atte,inc.</small>
  </footer>
</body>
</html>