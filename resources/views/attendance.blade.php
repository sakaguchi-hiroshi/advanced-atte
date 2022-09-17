<link href="/css/app.css" rel="stylesheet">
@extends('layouts.default')
  @section('date')
  <section class="date-container">
    <form action="/work/attendance/date/prev" method="POST" class="date-prev">
      @csrf
      <input type="hidden" name="date" value="{{$date->subDay()}}">
      <input class="btn-prev" type="submit" value="<">
    </form>
    <div class="date">
      <p class="date">{{ $date->addDay()->format('Y-m-d') }}</p>
    </div>
    <form action="/work/attendance/date/next" method="POST" class="date-next">
      @csrf
      <input type="hidden" name="date" value="{{$date->addDay()}}">
      <input class="btn-next" type="submit" value=">">
    </form>
  </section>
  @endsection
  @section('attendance')
  <section class="show-attendance">
    <table class="attendance-table">
      <tr class="attendance-tr">
        <th class="attendance-th">名前</th>
        <th class="attendance-th">勤務開始</th>
        <th class="attendance-th">勤務終了</th>
        <th class="attendance-th">休憩時間</th>
        <th class="attendance-th">勤務時間</th>
      </tr>
      @foreach($attendances as $workTime)
      <tr class="attendance-tr">
        <td class="attendance-td">{{ $workTime->user->name }}</td>
        <td class="attendance-td">{{ \Carbon\Carbon::createFromTimeString($workTime->start_time)->format('H:i:s') }}</td>
        <td class="attendance-td">{{ \Carbon\Carbon::createFromTimeString($workTime->end_time)->format('H:i:s') }}</td>
        <td class="attendance-td">{{ \Carbon\Carbon::createFromTimeString($workTime->total_break_time)->format('H:i:s') }}</td>
        <td class="attendance-td">{{ \Carbon\Carbon::createFromTimeString($workTime->total_hours_worked)->format('H:i:s') }}</td>
      </tr>
      @endforeach
    </table>
    {{ $attendances->links() }}
  </section>
  @endsection