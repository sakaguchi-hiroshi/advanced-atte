<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
</head>
<body>
  <form action="/work/calendar/sub/month" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{$dt->subMonthNoOverflow()}}">
    <input type="submit" value="前月">
  </form>
  <form action="/work/calendar/add/month" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{$dt->addMonthNoOverflow(2)}}">
    <input type="submit" value="次月">
  </form>
  <form action="/work/calendar/select/month" method="POST">
    @csrf
    <label for="date">検索したい月</label>
    <input type="month" name="date" id="date">
    <input type="submit" value="検索">
  </form>
  <h1>{{$dt->subMonth()->month}}月</h1>
  <table class="table table-bordered">
    <thead>
      <tr>
        @foreach(['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
        <th>{{$dayOfWeek}}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
      @if ($date->dayOfWeek == 0)
      <tr>
      @endif
        <td 
          @if ($date->month != $dt->month)
          class="bg-secondary"
          @endif
        >
          <a href="{{ url('/work/attendance', ['date' => $date]) }}">{{$date->day}}</a>
        </td>
      @if($date->dayOfWeek == 6)
      </tr>
      @endif
      @endforeach
    </tbody>
  </table>
</body>
</html>