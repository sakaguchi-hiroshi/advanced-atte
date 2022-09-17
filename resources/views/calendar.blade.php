@extends('layouts.default')
  @section('selected')
  <section class="selected">
    <form class="calendar__select-month" class="select-month" action="/work/calendar/select/month" method="POST">
      @csrf
      <label for="date">検索したい月</label><br>
      <input type="month" name="date" id="date">
      <input type="submit" value="検索">
    </form>
    <div class="calendar__operation-month">
      <form action="/work/calendar/sub/month" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{$dt->copy()->subMonthNoOverflow()}}">
        <input type="submit" value="前月">
      </form>
      <h1 class="this-month">{{$dt->copy()->month}}月</h1>
      <form action="/work/calendar/add/month" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{$dt->copy()->addMonthNoOverflow()}}">
        <input type="submit" value="次月">
      </form>
    </div>
  </section>
  @endsection
  @section('calendar')
  <table class="calendar-table">
    <thead>
      <tr class="calendar-tr">
        @foreach(['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
        <th class="calendar-th">{{$dayOfWeek}}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($dates as $date)
      @if ($date->dayOfWeek == 0)
      <tr class="calendar-tr">
      @endif
        <td class="calendar-td"
        @if ($date->month != $dt->month)
        id="bg-secondary"
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
  @endsection
