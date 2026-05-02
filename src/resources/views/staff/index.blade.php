@extends('layouts.app')

@section('content')
    <p class="attendance__status">{{ $status }}</p>
    <p class="attendance__date">{{ now()->format('Y年n月j日') }}</p>
    <p class="attendance__Time">{{ now()->format('H:i') }}</p>

    <form action="{{ route('staff.store') }}" class="attendance__submit" method="POST">
        @csrf

        @if ($status === '勤務外')
            <button name="action" value="clock_in" class="attendance__btn">出勤</button>
        @elseif ($status === '出勤中')
            <button name="action" value="clock_out" class="attendance__btn">退勤</button>
            <button name="action" value="break_start" class="attendance__btn">休憩</button>
        @elseif ($status === '休憩中')
            <button name="action" value="break_end" class="attendance__btn">休憩戻</button>
        @else
            <p>お疲れ様でした。</p>
        @endif

    </form>

@endsection