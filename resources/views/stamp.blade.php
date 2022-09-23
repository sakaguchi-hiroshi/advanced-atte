@extends('layouts.default')
@section('message')
<p class="message-user">{{$authuser->name}}さんお疲れ様です！
</p>
<p>{{ session('message')}}</p>
@endsection
@section('stamp')
<section class="stamp">
  <form class="stamp__start-time" action="/work/start" method="POST">
    @csrf
    <button class="btn-stamp" type="submit" >勤務開始</button>
  </form>
  <form class="stamp__end-time" action="/work/end" method="POST">
    @csrf
    <button class="btn-stamp" type="submit">勤務終了</button>
  </form>
  <form class="stamp__break-in" action="/work/break/in" method="POST">
    @csrf
    <button class="btn-stamp" type="submit">休憩開始</button>
  </form>
  <form class="stamp__break-out" action="/work/break/out" method="POST">
    @csrf
    <button class="btn-stamp" type="submit">休憩終了</button>
  </form>
</section>
@endsection