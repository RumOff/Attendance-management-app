@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/staff/index.css') }}">
@endsection

@section('content')
    <div class="container">

        <form action="{{ route('staff.store') }}" class="form" method="POST">
        @csrf
            <p class="attendance__status">{{ $status }}</p>
            <p class="attendance__date">{{ now()-> isoFormat('Y年M月D日(ddd)') }}</p>
            <p class="attendance__Time">{{ now()->format('H:i') }}</p>

            @if ($status === '勤務外')
                <button name="action" value="clock_in" class="attendance__btn btn-black">出勤</button>
            @elseif ($status === '出勤中')
                <button name="action" value="clock_out" class="attendance__btn btn-black">退勤</button>
                <button name="action" value="break_start" class="attendance__btn btn-break">休憩入</button>
            @elseif ($status === '休憩中')
                <button name="action" value="break_end" class="attendance__btn btn-break">休憩戻</button>
            @else
                <p class="attendance__msg">お疲れ様でした。</p>
            @endif

        </form>
    </div>

@endsection