<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
</head>
<body>
  <table class="table table-bordered">
    <thead>
      <tr>
        @foreach(['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
        <th>{{$dayOfWeek}}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        <td
          @if($date->month != $currentMonth)
          class="bg-secondary"
          @endif
        >
          {{$date->day}}
        </td>
      @if($date->dayOfWeek == 6)
      </tr>
      @endif
      @endforeach
    </tbody>
  </table>
</body>
</html>