@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/staff/history.css') }}">
@endsection

@section('content')

    <div class="container">
        <div class="content">
            <h1 class="page__title">
                勤怠一覧
            </h1>

            {{-- カレンダー --}}
            <div class="month-nav">
                <a href="{{ route('staff.history') }}" class="month-nav__link"><span>←</span> 前月</a>

                <div class="month-nav__display">
                    <img src="{{ asset('images/Schedule icon.png') }}" alt="カレンダー">
                    <span>{{ $currentMonth->format('Y/m') }}</span>
                </div>

                <a href="{{ route('staff.history') }}" class="month-nav__link">翌月 <span>→</span></a>
            </div>

            {{-- テーブル --}}
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($dates as $date)

                        @php
                        $key = $date->format('Y-m-d');
                        $attendance = $attendances[$key] ?? null;
                        @endphp

                        <tr>
                            {{-- 日付 --}}
                            <td>
                                {{ $date->Isoformat('MM/DD(ddd)') }}
                            </td>

                            {{-- 出勤 --}}
                            <td>
                                @if ($attendance && $attendance->clock_in)
                                    {{ $attendance->clock_in->format('H:i') ?? null }}
                                @endif
                            </td>

                            {{-- 退勤 --}}
                            <td>
                                @if ($attendance && $attendance->clock_out)
                                {{ $attendance->clock_out->format('H:i') ?? null }}
                                @endif
                            </td>

                            {{-- 休憩 --}}
                            <td>
                                
                                @if ($attendance && $attendance->break_minutes !== null)
                                    {{ floor($attendance->break_minutes / 60) }}:{{ str_pad($attendance->break_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @endif
                            </td>

                            {{-- 合計 --}}
                            <td>
                                @if ($attendance && $attendance->total_minutes !== null)
                                    {{ floor($attendance->total_minutes / 60) }}:{{ str_pad($attendance->total_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                @endif
                            </td>

                            {{-- 詳細 --}}
                            <td>
                                @if($attendance && $attendance->id !== null)
                                    <a href="/attendance/detail/{{ $attendance->id }}" class="history__detail">詳細</a>
                                @else
                                    <p class="history__detail">詳細</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection